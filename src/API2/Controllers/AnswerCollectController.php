<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer;
use Illuminate\Contracts\Routing\ResponseFactory;

class AnswerCollectController extends Controller
{
    /**
     * Collect an answer.
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
        if ($answer->collected($user)) {
            return $response->json(['message' => [trans('plus-question::answers.collect.collected')]], 422);
        }

        $answer->collect($user);

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
        if (! $answer->collected($user)) {
            return $response->json(['message' => [trans('plus-question::answers.collect.not-collected')]], 422);
        }

        $answer->unCollect($user);

        return $response->json(['message' => [trans('plus-question::messages.success')]], 204);
    }

    /**
     * A list of answer for collections.
     *
     * @author bs<414606094@qq.com>
     * @param  \Illuminate\Http\Request $request
     * @param  \SlimKit\PlusQuestion\Models\Answer $answer
     * @param  \Illuminate\Contracts\Routing\ResponseFactory $response
     * @return mixed
     */
    public function index(Request $request, ResponseFactory $response)
    {
    }
}
