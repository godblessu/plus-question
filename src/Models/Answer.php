<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Zhiyi\Plus\Models\Like;
use Zhiyi\Plus\Models\Reward;
use Zhiyi\Plus\Models\Collection;
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

    /**
     * Has be likes for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * Has collectors for answer.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function collectors()
    {
        return $this->morphMany(Collection::class, 'collectible');
    }

    /**
     * Has rewarders for answer.
     *
     * @return [type] [description]
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function rewarders()
    {
        return $this->morphMany(Reward::class, 'rewardable');
    }
}
