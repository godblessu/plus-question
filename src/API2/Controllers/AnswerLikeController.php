<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer;
use Illuminate\Contracts\Routing\ResponseFactory;

class AnswerLikeController extends Controller
{
    /**
     * Like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function store(Request $request, Answer $answer, ResponseFactory $response)
    {
        $user = $request->user()->id;
        if ($answer->liked($user)) {
            return $response->json(['message' => [trans('plus-question::answers.like.liked')]], 422);
        }

        $answer->like($user);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 201);
    }

    /**
     * Cancel like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function destroy(Request $request, Answer $answer, ResponseFactory $response)
    {
        $user = $request->user()->id;
        if (! $answer->liked($user)) {
            return $response->json(['message' => [trans('plus-question::answers.like.not-liked')]], 422);
        }

        $answer->unlike($user);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 204);
    }

    /**
     * A list of users who like an answer.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function index(Request $request, Answer $answer, ResponseFactory $response)
    {
        $limit = $request->query('limit', 20);
        $after = $request->query('after', 0);
        $list = $answer->likes()->when($after, function ($query) use ($after) {
            return $query->where('id', '<', $after);
        })->take($limit)->orderBy('id', 'desc')->get();

        return $response->json($list, 200);
    }
}
