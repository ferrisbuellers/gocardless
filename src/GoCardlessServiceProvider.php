<?php

namespace Midnite81\GoCardless;

use Illuminate\Support\ServiceProvider;
use Midnite81\GoCardless\Contracts\Services\Client;
use Midnite81\GoCardless\Services\Client as ConcreteClient;

class GoCardlessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/gocardless.php' => config_path('gocardless.php')
        ]);

        $this->mergeConfigFrom(__DIR__ . '/../config/gocardless.php', 'gocardless');

        $this->loadRoutes();

        if (config('gocardless.publish_migrations')) {
            $this->loadMigrations();
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Client::class, ConcreteClient::class);
        $this->app->alias(Client::class, 'gocardless');
    }

    public function loadRoutes()
    {
        if (method_exists($this, 'loadRoutesFrom')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/webhook.php');
        } else {
            if (! $this->app->routesAreCached()) {
                require __DIR__ . '/../routes/webhook.php';
            }
        }
    }

    /**
     * Load the migrations
     */
    protected function loadMigrations()
    {
        if (method_exists($this, 'loadMigrationsFrom')) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        } else {
            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations')
            ], 'migrations');
        }
    }
}
