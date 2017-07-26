<?php

namespace SlimKit\PlusQuestion\Models;

use Zhiyi\Plus\Models\User;
use Illuminate\Database\Eloquent\Model;
use Zhiyi\Plus\Models\Concerns\HasAvatar;
use Zhiyi\Plus\Contracts\Model\ShouldAvatar as ShouldAvatarContract;

class Topic extends Model implements ShouldAvatarContract
{
    use HasAvatar;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['avatar'];

    /**
     * Get topic avatar attribute.
     *
     * @return string|null
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarAttribute()
    {
        return $this->avatar();
    }

    /**
     * Topic followers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function followers()
    {
        return $this->belongsToMany(User::class)
            ->using(TopicUser::class);
    }

    /**
     * Get avatar trait.
     *
     * @return string|int
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarKey()
    {
        return $this->getKey();
    }

    /**
     * Avatar prefix.
     *
     * @return string
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function getAvatarPrefix(): string
    {
        return 'question/topics';
    }
}
