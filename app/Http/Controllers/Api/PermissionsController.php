<?php

namespace App\Http\Controllers\Api;

use App\Transformers\PermissionTransformer;
use Illuminate\Http\Request;

class PermissionsController extends Controller
{
    public function index(){
        $permissions = $this->auth->user()->getAllPermissions();

        return $this->response->collection($permissions,new PermissionTransformer());

    }
}
