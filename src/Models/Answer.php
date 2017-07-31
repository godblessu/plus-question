<?php

namespace SlimKit\PlusQuestion\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Has the answer for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
