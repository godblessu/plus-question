<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use SlimKit\PlusQuestion\API2\Requests\PublishQuestion as PublishQuestionRequest;

class QuestionController extends Controller
{
    public function store(PublishQuestionRequest $request,
                          ResponseFactoryContract $response)
    {
        $user = $this->resolveUser(
            $request->user()
        );

        dd($request->all());
    }
}
