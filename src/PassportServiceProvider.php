<?php

namespace Dropwire\Passport;

use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as BaseServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
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

    /**
     * Register the authorization server.
     *
     * @return void
     */
    protected function registerAuthorizationServer()
    {
        $this->app->singleton(AuthorizationServer::class, function () {
            return tap($this->makeAuthorizationServer(), function ($server) {
                $server->enableGrantType(
                    $this->makeAuthCodeGrant(), Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    $this->makeRefreshTokenGrant(), Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    $this->makePasswordGrant(), Passport::tokensExpireIn()
                );

                $server->enableGrantType(
                    new PersonalAccessGrant, new DateInterval('P10Y')
                );

                $server->enableGrantType(
                    new ClientCredentialsGrant, Passport::tokensExpireIn()
                );

                if (Passport::$implicitGrantEnabled) {
                    $server->enableGrantType(
                        $this->makeImplicitGrant(), Passport::tokensExpireIn()
                    );
                }

                //Add additional grants through easily overloaded method
                $this->enableCustomGrants($server);
            });
        });
    }

    /**
     * Enables any additional custom grants when overloaded.
     *
     * @param  AuthorizationServer $server
     * @return void
     */
    public function enableCustomGrants(AuthorizationServer $server)
    {
        //
    }
}
