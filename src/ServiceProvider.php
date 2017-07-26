<?php

namespace SlimKit\Component\PlusQuestion;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Boorstrap the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function boot()
    {
        // Register a database migration path.
        $this->loadMigrationsFrom([dirname(__DIR__).'/database/migrations']);
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
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
                \SlimKit\Component\PlusQuestion\Handlers\PackageHandler::class,
            ],
            'plus-question:dev-handler' => [
                \SlimKit\Component\PlusQuestion\Handlers\DevPackageHandler::class,
            ]
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
        $this->loadHandleFrom('question-dev', 'plus-question:dev-handler');
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
