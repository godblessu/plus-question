<?php

namespace SlimKit\Component\PlusQuestion\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        $this->loadMigrationsFrom($this->app->make('path.question.migration'));

        // Register handler singleton.
        $this->registerHandlerSingletions();
    }

    /**
     * Register the service provider.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    public function register()
    {
        // Bind all of the package paths in the container.
        $this->app->instance('path.question', $path = dirname(dirname(__DIR__)));
        $this->app->instance('path.question.migration', $path.'/database/migrations');

        // register cntainer aliases
        $this->registerContainerAliases();

        // Register Plus package handlers.
        $this->registerPackageHandlers();
    }

    /**
     * Register Plus package handlers.
     *
     * @return void
     * @author Seven Du <shiweidu@outlook.com>
     */
    protected function registerHandlerSingletions()
    {
        // Owner handler.
        $this->app->singleton('plus-question:handler', function () {
            return new \SlimKit\Component\PlusQuestion\Handlers\PackageHandler();
        });

        // Develop handler.
        $this->app->singleton('plus-question:dev-handler', function ($app) {
            return new \SlimKit\Component\PlusQuestion\Handlers\DevPackageHandler($app);
        });
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
