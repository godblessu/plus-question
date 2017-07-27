<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use SlimKit\PlusQuestion\Models\User as UserModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\PublishQuestion as PublishQuestionRequest;

class QuestionController extends Controller
{
    public function store(PublishQuestionRequest $request,
                          ResponseFactoryContract $response,
                          QuestionModel $question,
                          TopicModel $topicModel,
                          UserModel $userModel)
    {
        $user = $this->resolveUser(
            $request->user()
        );

        // Get question base data.
        $subject = str_replace('?', '？', $request->input('subject'));
        $body = $request->input('body');
        $anonymity = $request->input('anonymity') ? 1 : 0;
        $amount = intval($request->input('amount')) ?: 0;
        $automaticity = $request->input('automaticity') ? 1 : 0;
        $look = $request->input('look') ? 1 : 0;
        $automaticity = $request->input('automaticity') ? 1 : 0;
        $topicsIDs = array_pluck((array) $request->input('topics', []), 'id');
        $usersIDs = array_pluck((array) $request->input('invitations', []), 'user');

        if ($automaticity && ! $amount) {
            return $response->json(['amount' => [trans('plus-question::questions.回答自动入账必须设置悬赏总额')]], 422);
        } elseif ($automaticity && count($usersIDs) !== 1) {
            return $response->json(['invitations' => [trans('plus-question::questions.回答自动入账只能邀请一人')]], 422);
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

        try {

            // Save question.
            $user->questions()->save($question);

            // Save relation.
            $user->getConnection()->transaction(function () use (
                $question, $user, $topics, $users,
                $topicModel, $topicsIDs
            ) {

                // Sync topics.
                $question->topics()->sync($topics);

                // Topics questions_count +1
                $topicModel->whereIn('id', $topicsIDs)->increment('questions_count', 1);

                // User questions_count +1
                $user->extra()->firstOrCreate([])->increment('questions_count', 1);
            });
        } catch (\Exception $exception) {

            // Delete Question.
            $question->delete();

            throw $exception;
        }

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }
}
