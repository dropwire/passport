# Dropwire Passport

An extension of the official Laravel Passport package.

The official Laravel Passport package is well documented and useful, but the Dropwire team needed to adjust some of its functionality in such a way as to suit our needs while keeping maintenance to a minimum. As such, this package is not a fork, but merely a complimentary package that extends the official package by implementing an alternative service provider.

Currently Dropwire Passport offers the following functionality in addition to the base Laravel Passport package:

* Client IDs are random 20 character strings instead of auto-incrementing integers;
* `Dropwire\Passport\Http\Middleware\CheckFirstPartyClient` middleware class to authenticate a client as a first party client;
* Easier enabling of new or custom grants by overloading `Dropwire\Passport\PassportServiceProvider::enableCustomGrants()`;
* By default, all exceptions and errors raised by the OAuth server through one of Laravel Passport's included controllers will run through the `App\Exceptions\Handler` class like all other platform errors, or can otherwise be configured as desired.

## Installation

As Dropwire Passport is not registered with Packagist yet, the package must be added to composer as a custom repository first:

```sh
composer config repositories.dropwire_passport vcs https://github.com/dropwire/passport.git
```

Then import the package with composer:

```sh
composer require dropwire/passport:dev-master
```

> **Please note:** Dropwire Passport imports Laravel Passport as a dependency, so it is not necessary to also include Laravel Passport yourself.

Once Dropwire Passport has been imported, follow the instructions for installing/configuring Laravel Passport except for the following:

* Register the Dropwire Passport service provider, `Dropwire\Passport\PassportServiceProvider`, instead of the Laravel Passport service provider.

## Using the `CheckFirstPartyClient` Middleware

To use the `CheckFirstPartyClient` middleware, add the following middleware to the `$routeMiddleware` property of your `app/Http/Kernel.php` file:

```php
'auth.firstParty' => \Dropwire\Passport\Http\Middleware\CheckFirstPartyClient::class,
```

The middleware can then be registered for any route or route group with the key `auth.firstParty`.

## Enabling Custom Grants

In order to use your own custom grants, you must extend the `Dropwire\Passport\PassportServiceProvider` class and overload the `enableCustomGrants()` method.

The method accepts a single parameter of type `League\OAuth2\Server\AuthorizationServer` as the argument `$server`. Use the `enableGrantType()` method of the `AuthorizationServer` class to enable new grant types.

## Custom Error Handling

By default, Dropwire Passport eliminates the special error handling of the `HandlesOAuthErrors` trait, so that any errors raised will run through the `App\Exceptions\Handler` class like all other errors.

### Defining A Custom `HandlesOAuthErrors` Trait

If you wish to write your own version of the `HandlesOAuthErrors` trait, complete the following steps:

1. Create a copy of the `Laravel\Passport\Http\Controllers\HandlesOAuthErrors` class within your application, updating functionality as desired;
2. Extend the `Dropwire\Passport\PassportServiceProvider` class within your application;
3. Overload the `errorHandlerTrait` property on your custom service provider with the fully qualified class name of the class created in step 1; and
4. Replace the Dropwire Passport service provider with the new one in your `config/app.php` file.

### Reverting To Laravel Passport Error Handling

If you wish for error handling to be run the same as Laravel Passport package would by default, you can turn off custom error handling by completing the following steps:

1. Create a copy of the `Laravel\Passport\Http\Controllers\HandlesOAuthErrors` class within your application, updating functionality as desired;
2. Extend the `Dropwire\Passport\PassportServiceProvider` class within your application;
3. Overload the `useErrorHandler` property on your custom service provider, setting the value to `false`; and
4. Replace the Dropwire Passport service provider with the new one in your `config/app.php` file.
