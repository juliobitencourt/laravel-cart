<?php

namespace JulioBitencourt\Cart;

use Illuminate\Support\ServiceProvider;
use Illuminate\Session\Store as Session;

class CartServiceProvider extends ServiceProvider
{
    /**
     */
    public function boot()
    {
        $this->publishConfiguration();
        $this->publishMigrations();
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->bind('JulioBitencourt\Cart\Storage\StorageInterface', function ($app) {
            $storageDriver = $app['config']->get('laravel-cart.storage_driver', 'Session');

            switch ($storageDriver) {
                 case 'Session':
                     return new Storage\Session\SessionRepository($app['Illuminate\Session\Store']);
                     break;

                 case 'Database':
                     return new Storage\Eloquent\EloquentRepository($app['Storage\Eloquent\Entities\Cart']);
                     break;

                 default:
                     throw new \InvalidArgumentException('Invalid Cart storage driver');
                     break;
            }
        });
    }

    /**
     * Publish configuration file.
     */
    private function publishConfiguration()
    {
        $this->publishes([__DIR__.'/resources/config/laravel-cart.php' => config_path('laravel-cart.php')], 'config');
        $this->mergeConfigFrom(__DIR__.'/resources/config/laravel-cart.php', 'laravel-cart');
    }

    /**
     * Publish migration file.
     */
    private function publishMigrations()
    {
        $this->publishes([__DIR__.'/resources/migrations/' => base_path('database/migrations')], 'migrations');
    }
}
