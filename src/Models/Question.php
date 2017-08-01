<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    /**
     * Has topics for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'question_topic')
            ->using(QuestionTopic::class);
    }

    /**
     * Has invitation users for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function invitations()
    {
        return $this->belongsToMany(User::class, 'question_invitation');
    }

    /**
     * Has answers for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id', 'id');
    }

    /**
     * Has the user for question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Has watch users for the question.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function watchers()
    {
        return $this->belongsToMany(User::class, 'question_watcher')
            ->using(QuestionWatcher::class)
            ->withTimestamps();
    }
}
