<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Illuminate\Http\Request;
use SlimKit\PlusQuestion\Models\Topic as TopicModel;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;

class TopicQuestionController extends Controller
{
    /**
     * List all question for topic.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Contracts\Routing\ResponseFactory $response
     * @param \SlimKit\PlusQuestion\Models\Topic $topic
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function index(Request $request,
                          ResponseFactoryContract $response,
                          TopicModel $topic)
    {
        $limit = max(1, min(30, $request->query('limit', 20)));
        $offset = max(0, $request->query('offset', 0));
        $map = [
            'all' => function ($query) {
                $query->orderBy('id', 'desc');
            },
            'new' => function ($query) {
                $query->where('answers_count', 0)
                    ->orderBy('id', 'desc');
            },
            'hot' => function ($query) use ($topic) {
                $query->whereBetween('created_at', [
                    $topic->freshTimestamp()->subMonth(1),
                    $topic->freshTimestamp(),
                ]);
                $query->orderBy('answers_count', 'desc');
            },
            'reward' => function ($query) {
                $query->where('amount', '!=', 0)
                    ->orderBy('id', 'desc');
            },
            'excellent' => function ($query) {
                $query->where('excellent', '!=', 0)
                    ->orderBy('id', 'desc');
            },
        ];
        $type = in_array($type = $request->query('type', 'new'), array_keys($map)) ? $type : 'new';
        call_user_func($map[$type], $query = $topic->questions()->with('user'));
        $questions = $query->limit($limit)
            ->offset($offset)
            ->get();

        return $response->json($questions->map(function (QuestionModel $question) {
            if ($question->anonymity) {
                $question->addHidden('user');
                $question->user_id = 0;
            }

            return $question;
        }))->setStatusCode(200);
    }
}
