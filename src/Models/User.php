<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User as Model;

class User extends Model
{
    /**
     * The user follow topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function questionTopics()
    {
        return $this->belongsToMany(Topic::class, 'topic_user')
            ->using(TopicUser::class)
            ->withTimestamps();
    }

    /**
     * The user blong to topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function belongTopics()
    {
        return $this->belongsToMany(Topic::class, 'topic_expert')
            ->using(TopicExpert::class)
            ->withTimestamps();
    }

    /**
     * Has questions for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'user_id', 'id');
    }

    /**
     * Has watching questions for the user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function watchingQuestions()
    {
        return $this->belongsToMany(Question::class, 'question_watcher')
            ->using(QuestionWatcher::class)
            ->withTimestamps();
    }
}
