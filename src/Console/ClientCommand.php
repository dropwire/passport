<?php

namespace Dropwire\Passport\Console;

use Dropwire\Passport\ClientRepository;
use Laravel\Passport\Console\ClientCommand as BaseClientCommand;

class ClientCommand extends BaseClientCommand
{
    /**
     * Execute the console command.
     *
     * @param  \Dropwire\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle(ClientRepository $clients)
    {
        parent::handle($clients);
    }
}
