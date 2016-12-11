<?php

namespace Dropwire\Passport;

use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProvider;
use League\OAuth2\Server\Grant\PasswordGrant;

class PassportServiceProvider extends BaseServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../../laravel/passport/resources/views', 'passport');

        $this->deleteCookieOnLogout();

        if ($this->app->runningInConsole()) {
            $this->registerMigrations();

            $this->publishes([
                __DIR__.'/../../laravel/passport/resources/views' => base_path('resources/views/vendor/passport'),
            ], 'passport-views');

            $this->publishes([
                __DIR__.'/../../laravel/passport/resources/assets/js/components' => base_path('resources/assets/js/components/passport'),
            ], 'passport-components');

            $this->commands([
                \Laravel\Passport\Console\InstallCommand::class,
                Console\ClientCommand::class,
                \Laravel\Passport\Console\KeysCommand::class,
            ]);
        }
    }

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
