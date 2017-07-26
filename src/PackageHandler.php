<?php

namespace SlimKit\Component\PlusQuestion;

use Illuminate\Console\Command;

class PackageHandler extends \Zhiyi\Plus\Support\PackageHandler
{
    /**
     * The migrate handle.
     *
     * @param \Illuminate\Console\Command $command
     * @return mixed
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function migrateHandle(Command $command)
    {
        return $command->call('migrate');
    }
}
