<?php

namespace Dropwire\Passport\Http\Controllers;

use Dropwire\Passport\Http\Controllers\HandlesOAuthErrors;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController as BaseController;

class ApproveAuthorizationController extends BaseController
{
    use HandlesOAuthErrors;
}
