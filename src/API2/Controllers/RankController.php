<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use DB;
use Carbon\Carbon;
use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Answer as AnswerModel;

class RankController extends Controller
{

    /**
     * 获取解答排行榜.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @param  Carbon $datetime
     * @return mixed
     */
    public function answers(Request $request, AnswerModel $answerModel, Carbon $datetime)
    {
        $user = $request->user();
        $type = $request->query('type', 'day');
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        switch ($type) {
            case 'day':
                $date = $datetime->subDay();
                break;
            case 'week':
                $date = $datetime->subWeek();
                break;
            case 'month':
                $date = $datetime->subMonth();
                break;
            default:
                $date = $datetime->subDay();
                break;
        }

        $answers = $answerModel->select('user_id', DB::raw('count(user_id) as count'))
        ->where('created_at', '>', $date)
        ->with(['user' => function ($query) {
            return $query->select('id', 'name');
        }])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return response()->json($answerModel->getConnection()->transaction(function () use ($answers, $user, $date, $answerModel, $offset) {
            $data = [
                'user_count' => 0,
                'ranks' => [],
            ];

            $data['ranks'] = $answers->map(function ($answer, $key) use ($user, $offset) {
                $answer->user->addHidden('extra');
                $answer->user->count = (int) $answer->count; // 回答数
                $answer->user->rank = $key + $offset + 1; // 排名

                $answer->user->following = $answer->user->hasFollwing($user);
                $answer->user->follower = $answer->user->hasFollower($user);

                return $answer->user;
            });

            $data['user_count'] = $answerModel->where('created_at', '>', $date)
                ->where('user_id', $user->id)
                ->get()->count();

            return $data;
        }), 200);
    }

    /**
     * 获取回答点赞数排行榜.
     *
     * @author bs<414606094@qq.com>
     * @param  Illuminate\Http\Request $request
     * @param  SlimKit\PlusQuestion\Models\Answer $answerModel
     * @param  Carbon $datetime
     * @return mixed
     */
    public function likes(Request $request, AnswerModel $answerModel)
    {
        $user = $request->user();
        $limit = $request->query('limit', 10);
        $offset = $request->query('offset', 0);

        $answers = $answerModel->select('user_id', DB::raw('sum(likes_count) as count'))
        ->with(['user' => function ($query) {
            return $query->select('id', 'name');
        }])
        ->groupBy('user_id')
        ->orderBy('count', 'desc')
        ->offset($offset)
        ->take($limit)
        ->get();

        return response()->json($answerModel->getConnection()->transaction(function () use ($answers, $user, $answerModel, $offset) {
            $data = [
                'user_count' => 0,
                'ranks' => [],
            ];

            $data['ranks'] = $answers->map(function ($answer, $key) use ($user, $offset) {
                $answer->user->addHidden('extra');
                $answer->user->count = (int) $answer->count; // 回答点赞数
                $answer->user->rank = $key + $offset + 1; // 排名

                $answer->user->following = $answer->user->hasFollwing($user);
                $answer->user->follower = $answer->user->hasFollower($user);

                return $answer->user;
            });

            $data['user_count'] = $answerModel->where('user_id', $user->id)->get()->sum('likes_count');

            return $data;
        }), 200);
    }
}