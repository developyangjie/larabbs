<?php

namespace App\Http\Controllers\Api;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use Dingo\Api\Routing\Helpers;

class Controller extends BaseController
{
    use Helpers;

    public function __construct()
    {
        app('Dingo\Api\Exception\Handler')->register(function (AuthorizationException $exception) {
            $this->response->error($exception->getMessage(),403);
        });
    }
}
