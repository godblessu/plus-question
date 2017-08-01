<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicExpertController extends Controller
{
    public function index(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $after = $request->query('after');
        $users = $topic->experts()
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->get();

        dd($users);
    }
}
