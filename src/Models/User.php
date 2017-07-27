<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User as Model;

class User extends Model
{
    /**
     * The user follow topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function questionTopics()
    {
        return $this->belongsToMany(Topic::class, 'topic_user')
            ->using(TopicUser::class);
    }

    /**
     * The user blong to topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function belongTopics()
    {
        return $this->belongsToMany(Topic::class, 'topic_expert')
            ->using(TopicExpert::class);
    }
}
