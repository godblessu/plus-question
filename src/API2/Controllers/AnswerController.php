<?php

namespace SlimKit\PlusQuestion\API2\Controllers;

use Zhiyi\Plus\Contracts\FindMarkdownFileTrait;
use SlimKit\PlusQuestion\Models\Question as QuestionModel;

class AnswerController extends Controller
{
    use FindMarkdownFileTrait;

    public function store(QuestionModel $question)
    {
        // todo.
    }
}
