<?php

namespace Dropwire\Passport\Http\Middleware;

use Closure;
use Dropwire\Passport\ClientRepository;
use Illuminate\Auth\AuthenticationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class CheckFirstPartyClient
{
    /**
     * The Resource Server instance.
     *
     * @var ResourceServer
     */
    protected $server;

    /**
     * @var ClientRepository
     */
    protected $repository;

    /**
     * Create a new middleware instance.
     *
     * @param  ResourceServer  $server
     * @return void
     */
    public function __construct(ResourceServer $server, ClientRepository $repository)
    {
        $this->server = $server;
        $this->repository = $repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            throw new AuthenticationException;
        }

        $client = $this->repository->find($psr->getAttribute('oauth_client_id'));

        if (!$client || !$client->firstParty()) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
