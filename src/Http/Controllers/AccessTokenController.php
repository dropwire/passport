<?php

namespace Dropwire\Passport\Http\Controllers;

use Dropwire\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Http\Controllers\AccessTokenController as BaseController;

class AccessTokenController extends BaseController
{
    use HandlesOAuthErrors;
}
