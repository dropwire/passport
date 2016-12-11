<?php

namespace Dropwire\Passport;

use Laravel\Passport\Passport;
use League\OAuth2\Server\Grant\PasswordGrant;

class PassportServiceProvider extends ServiceProvider
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
}
