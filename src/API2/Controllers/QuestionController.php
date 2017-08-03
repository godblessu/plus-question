<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Concerns\FindMarkdownFileTrait;
use SlimKit\PlusQuestion\Models\User as UserModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\UpdateQuestion as UpdateQuestionRequest;
use SlimKit\PlusQuestion\API2\Requests\PublishQuestion as PublishQuestionRequest;

class QuestionController extends Controller
{
    use FindMarkdownFileTrait;

    /**
     * List all questions.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $questionModel
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, QuestionModel $questionModel)
    {
        $limit = max(1, min(30, $request->query('limit', 20)));
        $offset = max(0, $request->query('offset', 0));
        $map = [
            'all' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'new' => function ($query) {
                $query->where('answers_count', 0)
                    ->orderBy('id', 'desc');
            },
            'hot' => function ($query) use ($questionModel) {
                $query->whereBetween('created_at', [
                    $questionModel->freshTimestamp()->subMonth(1),
                    $questionModel->freshTimestamp(),
                ]);
                $query->orderBy('answers_count', 'desc');
            },
            'reward' => function ($query) {
                $query->where('amount', '!=', 0)
                    ->orderBy('id', 'desc');
            },
            'excellent' => function ($query) {
                $query->where('excellent', '!=', 0)
                    ->orderBy('id', 'desc');
            },
        ];
        $type = in_array($type = $request->query('type', 'new'), array_keys($map)) ? $type : 'new';
        call_user_func($map[$type], $query = $questionModel->limit($limit)->offset($offset));
        $questions = $query->get();
        $questions->load('user');

        return $response->json($questions->map(function (QuestionModel $question) {
            if ($question->anonymity) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            return $question;
        }))->setStatusCode(200);
    }

    public function show(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $userID = $request->user('api')->id ?? 0;
        $loadMap = [
            'topics', 'invitations',
            'answers' => function ($query) {
                $query->where('invited', '!=', 0);
                $query->where('adoption', 0);
                $query->orderBy('id', 'desc');
            },
            'answers.user'
        ];
        $answerResolveCall = function (AnswerModel $answer) use ($userID, $question) {
            if ($answer->anonymity && $answer->user_id !== $userID) {
                $answer->addHidden('user');
                $answer->user_id = 0;
            }

            if ($question->automaticity || ($question->lock && $answer->adoption)) {
                # code...
            }

            return $answer;
        };

        if (! $question->anonymity || $userID === $question->user_id) {
            $loadMap[] = 'user';
        } elseif ($question->anonymity) {
            $question->user_id = 0;
        }
        $question->load($loadMap);
        $question->addHidden('answers');
        $question->invitation_answers = $question->answers->map($answerResolveCall);
        $question->adoption_answers = $question->answers()
            ->with('user')
            ->where('adoption', '!=', 0)
            ->get()
            ->map($answerResolveCall);
        
        return $response->json($question, 200);
    }

    /**
     * Publish a question.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\PublishQuestion $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @param \SlimKit\PlusQuestion\Models\Topic $topicModel
     * @param \SlimKit\PlusQuestion\Models\User $userModel
     * @param \Zhiyi\Plus\Models\WalletCharge $charge
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(PublishQuestionRequest $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          TopicModel $topicModel,
                          UserModel $userModel,
                          WalletChargeModel $charge)
    {
        $user = $request->user();

        // Get question base data.
        $subject = $request->input('subject');
        $body = $request->input('body');
        $anonymity = $request->input('anonymity') ? 1 : 0;
        $amount = intval($request->input('amount')) ?: 0;
        $look = $request->input('look') ? 1 : 0;
        $automaticity = $request->input('automaticity') ? 1 : 0;
        $topicsIDs = array_pluck((array) $request->input('topics', []), 'id');
        $usersIDs = array_pluck((array) $request->input('invitations', []), 'user');
        $images = $this->findMarkdownImageNotWithModels($body ?: '');

        if ($automaticity && ! $amount) {
            return $response->json(['amount' => [trans('plus-question::questions.回答自动入账必须设置悬赏总额')]], 422);
        } elseif ($automaticity && count($usersIDs) !== 1) {
            return $response->json(['invitations' => [trans('plus-question::questions.回答自动入账只能邀请一人')]], 422);
        } elseif ($look && ! $amount) {
            return $response->json(['amount' => [trans('plus-question::question.开启围观必须设置悬赏金额')]], 422);
        }

        // Find topics.
        $topics = empty($topicsIDs) ? collect() : $topicModel->whereIn('id', $topicsIDs)->get();

        // Find users.
        $users = empty($usersIDs) ? collect() : $userModel->whereIn('id', $usersIDs)->get();

        $question->subject = $subject;
        $question->body = $body;
        $question->anonymity = $anonymity;
        $question->amount = $amount;
        $question->automaticity = $automaticity;
        $question->look = $look;

        // Charge
        $charge->user_id = $user->id;
        $charge->channel = 'system';
        $charge->action = 0;
        $charge->amount = $amount;
        $charge->subject = trans('plus-question::questions.发布悬赏问答');
        $charge->body = trans('plus-question::questions.发布悬赏问答《%s》', ['subject' => $question->subject]);
        $charge->status = 1;

        try {

            // Save question.
            $user->questions()->save($question);

            // Save relation.
            $user->getConnection()->transaction(function () use (
                $question, $user, $topics, $users,
                $topicModel, $topicsIDs,
                $charge, $images
            ) {

                // Sync topics.
                $question->topics()->sync($topics);

                // Topics questions_count +1
                $topicModel->whereIn('id', $topicsIDs)->increment('questions_count', 1);

                // User questions_count +1
                $user->extra()->firstOrCreate([])->increment('questions_count', 1);

                // Sync invitations
                if (! empty($users)) {
                    $question->invitations()->sync($users);
                }

                // Save charage
                if ($charge->amount) {
                    $user->walletCharges()->save($charge);
                    $user->wallet()->decrement('balance', $charge->amount);
                }

                // Update images.
                $images->each(function ($image) use ($question) {
                    $image->channel = 'question:images';
                    $image->raw = $question->id;
                    $image->save();
                });
            });
        } catch (\Exception $exception) {

            // Delete Question.
            $question->delete();

            throw $exception;
        }

        // 给用户发送邀请通知.
        $users->each(function (UserModel $item) use ($user, $question) {
            $item->sendNotifyMessage(
                'question',
                trans('plus-question::questions.invitation', [
                    'user' => $user->name,
                    'question' => $question->subject,
                ]),
                [
                    'user' => $user,
                    'question' => $question,
                ]
            );
        });

        return $response->json([
            'message' => [trans('plus-question::messages.success')],
            'question' => $question,
        ], 201);
    }

    /**
     * Update a question.
     *
     * @param \SlimKit\PlusQuestion\API2\Requests\UpdateQuestion $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Question $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function update(UpdateQuestionRequest $request,
                           ResponseFactoryContract $response,
                           QuestionModel $question)
    {
        foreach (array_filter($request->only(['subject', 'body'])) as $key => $value) {
            $question->$key = $value;
        }

        $question->save();

        return $response->make(null, 204);
    }
}
