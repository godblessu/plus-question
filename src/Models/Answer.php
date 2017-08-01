<?php

namespace SlimKit\PlusQuestion\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    /**
     * Has the question for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|null
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
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
