<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserQuestionController extends Controller
{
    /**
     * Watch a question.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param QuestionModel $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function store(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $user = $request->user();

        if ($user->watchingQuestions()->newPivotStatementForId($question->id)->first()) {
            return $response->json(['message' => [trans('plus-question::users.questions.watched')]], 422);
        }

        $user->watchingQuestions()->attach($question);

        return $response->make('', 204);
    }

    /**
     * Unwatch a question.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @param QuestionModel $question
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function destroy(Request $request, ResponseFactoryContract $response, QuestionModel $question)
    {
        $user = $request->user();

        if (! $user->watchingQuestions()->newPivotStatementForId($question->id)->first()) {
            return $response->json(['message' => [trans('plus-question::users.questions.not-watching')]], 422);
        }

        $user->watchingQuestions()->detach($question);

        return $response->make('', 204);
    }
}
