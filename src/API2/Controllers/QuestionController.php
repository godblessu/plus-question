<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use SlimKit\PlusQuestion\Models\User as UserModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Zhiyi\Plus\Models\WalletCharge as WalletChargeModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\UpdateQuestion as UpdateQuestionRequest;
use SlimKit\PlusQuestion\API2\Requests\PublishQuestion as PublishQuestionRequest;

class QuestionController extends Controller
{
    public function store(PublishQuestionRequest $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          TopicModel $topicModel,
                          UserModel $userModel,
                          WalletChargeModel $charge)
    {
        $user = $this->resolveUser(
            $request->user()
        );

        // Get question base data.
        $subject = $request->input('subject');
        $body = $request->input('body');
        $anonymity = $request->input('anonymity') ? 1 : 0;
        $amount = intval($request->input('amount')) ?: 0;
        $look = $request->input('look') ? 1 : 0;
        $automaticity = $request->input('automaticity') ? 1 : 0;
        $topicsIDs = array_pluck((array) $request->input('topics', []), 'id');
        $usersIDs = array_pluck((array) $request->input('invitations', []), 'user');

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
                $charge
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

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
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
