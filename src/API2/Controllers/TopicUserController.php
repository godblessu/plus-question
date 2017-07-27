<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicUserController extends Controller
{
    public function index(Request $request, ResponseFactoryContract $response)
    {
        $user = $this->resolveUser($request->user());

        var_dump($user);
        exit;
    }
}
