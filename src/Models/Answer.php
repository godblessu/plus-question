<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Has the question for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }

    /**
     * Has user for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Has onlookers for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function onlookers()
    {
        return $this->belongsToMany(User::class, 'answer_onlooker', 'answer_id', 'user_id')
            ->using(AnswerOnlooker::class)
            ->withTimestamps();
    }
}
