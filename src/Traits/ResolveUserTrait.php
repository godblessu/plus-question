<?php

namespace SlimKit\PlusQuestion\Traits;

use SlimKit\PlusQuestion\Models\User;
use Zhiyi\Plus\Models\User as BaseUser;

trait ResolveUserTrait
{
    /**
     * Resolve user.
     *
     * @param \Zhiyi\Plus\Models\User $user
     * @return \SlimKit\PlusQuestion\Models\User
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function resolveUser(BaseUser $user): User
    {
        $model = new User();
        $model->setRawAttributes($user->getAttributes(), true);
        $model->setRelations($user->getRelations());
        $model->setConnection($user->getConnectionName());
        $model->exists = true;

        return $model;
    }
}
