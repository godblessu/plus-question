<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use Zhiyi\Plus\Models\User as UserModel;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicExpertController extends Controller
{
    /**
     * Get all experts for the topics.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request, ResponseFactoryContract $response, TopicModel $topic)
    {
        $userID = $request->user('api')->id ?? 0;
        $after = $request->query('after');
        $users = $topic->experts()
            ->with('tags')
            ->when($after, function ($query) use ($after) {
                return $query->where('id', '<', $after);
            })
            ->limit(20)
            ->orderBy('id', 'desc')
            ->get();

        return $response->json($users->map(function (UserModel $user) use ($userID) {
            $user->following = $user->hasFollwing($userID);
            $user->follower = $user->hasFollower($userID);

            return $user;
        }))->setStatusCode(200);
    }
}
