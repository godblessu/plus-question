<?php

namespace SlimKit\Component\PlusQuestion;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
        // register cntainer aliases
        $this->registerContainerAliases();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Register container aliases.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerContainerAliases()
    {
        $aliases = [
            'plus-question:handler' => [
                SlimKit\Component\PlusQuestion\PackageHandler::class,
            ],
        ];

        foreach ($aliases as $key => $aliases) {
            foreach ($aliases as $key => $alias) {
                $this->app->alias($key, $alias);
            }
        }
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerPackageHandlers()
    {
        $this->loadHandleFrom('question', 'plus-question:handler');
    }

    /**
     * Register handler.
     *
     * @param string $name
     * @param \Zhiyi\Plus\Support\PackageHandler|string $handler
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    private function loadHandleFrom(string $name, $handler)
    {
        \Zhiyi\Plus\Support\PackageHandler::loadHandleFrom($name, $handler);
    }
}
