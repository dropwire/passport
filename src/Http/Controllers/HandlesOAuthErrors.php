<?php

namespace Dropwire\Passport\Http\Controllers;

use Exception;
use Illuminate\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;
use Zend\Diactoros\Response as Psr7Response;

trait HandlesOAuthErrors
{
    /**
     * Perform the given callback without exception handling.
     *
     * @param  \Closure  $callback
     * @return Response
     */
    protected function withErrorHandling($callback)
    {
        /*
         * In this version of the trait, do not catch any errors so that the
         * error handler can format and return errors like all other platform
         * errors
         */
        return $callback();
    }

    /**
     * Get the exception handler instance.
     *
     * @return \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected function exceptionHandler()
    {
        return Container::getInstance()->make(ExceptionHandler::class);
    }
}
