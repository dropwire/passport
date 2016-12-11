<?php

namespace Dropwire\Passport;

use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;

class PassportServiceProvider extends BaseServiceProvider
{
    /**
     * Register Passport's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        if (Passport::$runsMigrations) {
            return $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'passport-migrations');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerOverloads();

        $this->registerAuthorizationServer();

        $this->registerResourceServer();

        $this->registerGuard();
    }

    /**
     * Register overloaded classes.
     *
     * @return void
     */
    public function registerOverloads()
    {
        $this->app->bind(
            \Laravel\Passport\ClientRepository::class,
            \Dropwire\Passport\ClientRepository::class
        );
    }
}
