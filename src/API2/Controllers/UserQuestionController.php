<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class UserQuestionController extends Controller
{
    /**
     * List watched questions for the authenticated user.
     *
     * @param Request $request
     * @param ResponseFactoryContract $response
     * @return moxed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response)
    {
        $user = $request->user();
        $limit = max(1, min(30, $request->query('limit', 20)));
        $offset = max(0, $request->query('offset', 0));

        $questions = $user->watchingQuestions()
            ->with('user')
            ->orderBy($user->watchingQuestions()->createdAt(), 'desc')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return $response->json($questions->map(function (QuestionModel $question) use ($user) {
            if ($question->anonymity && $question->user_id !== $user->id) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            return $question;
        }))->setStatusCode(200);
    }

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
