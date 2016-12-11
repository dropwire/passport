<?php

namespace Dropwire\Passport\Bridge;

use Dropwire\Passport\ClientRepository as ClientModelRepository;
use Laravel\Passport\Bridge\ClientRepository as BaseClientRepository;

class ClientRepository extends BaseClientRepository
{
    /**
     * Create a new repository instance.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function __construct(ClientModelRepository $clients)
    {
        $this->clients = $clients;
    }
}
