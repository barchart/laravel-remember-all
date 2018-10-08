<?php

namespace Barchart\Laravel\RememberAll;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class RememberAllServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        Auth::extend('rememberall', function ($app, $name, $config) {
            $provider = $app['auth']->createUserProvider($config['provider'] ?? null);

            $guard = new SessionGuard($name, $provider, $app['session.store'], request(), $config['expire'] ?? null);

            if (method_exists($guard, 'setCookieJar')) {
                $guard->setCookieJar($app['cookie']);
            }

            if (method_exists($guard, 'setDispatcher')) {
                $guard->setDispatcher($app['events']);
            }

            if (method_exists($guard, 'setRequest')) {
                $guard->setRequest($app->refresh('request', $guard, 'setRequest'));
            }

            return $guard;
        });

        Auth::provider('database', function ($app, array $config) {
            $connection = $app['db']->connection();

            return new DatabaseUserProvider($connection, $app['hash'], $config['table']);
        });

        Auth::provider('eloquent', function ($app, array $config) {
            return new EloquentUserProvider($app['hash'], $config['model']);
        });
    }
}
