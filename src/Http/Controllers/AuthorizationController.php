<?php

namespace Dropwire\Passport\Http\Controllers;

use Dropwire\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Http\Controllers\AuthorizationController as BaseController;

class AuthorizationController extends BaseController
{
    use HandlesOAuthErrors;
}
