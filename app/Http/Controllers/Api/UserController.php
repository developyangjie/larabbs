<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Http\Requests\Api\WeappUserRequest;
use App\Models\Image;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(UserRequest $request){
        $verification = \Cache::get($request->verification_key);

        if(!$verification){
            return $this->response->error("验证码已过期",401);
        }

        if(!hash_equals((string)$verification['code'],$request->verification_code)){
            return $this->response->error("验证码错误",422);
        }

        $user = User::create([
            "name"=>$request->name,
            "phone"=>$verification['phone'],
            "password"=>bcrypt($request->password)
        ]);

        \Cache::forget($request->verification_key);

        return $this->response->item($user,new UserTransformer())->setMeta([
            "access_token" => \Auth::guard("api")->login($user),
            "token_type" => "Bearer",
            "expires_in" => \Auth::guard("api")->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    public function me(){
        return $this->response->item($this->auth->user(),new UserTransformer());
    }

    public function update(UserRequest $request){
        $attributes = $request->only(["email","name","introduction","registration_id"]);

        if($request->avatar_image_id){
            $image = Image::find($request->avatar_image_id);

            $attributes["avatar"] = $image->path;
        }

        $user = $this->auth->user();

        $user->update($attributes);

        return $this->response->item($user,new UserTransformer());
    }

    public function weappStore(WeappUserRequest $request){
        if(!isset($request->code) || empty($request->code)){
            return $this->response->errorUnauthorized("code 不存在");
        }

        $miniProgram = \EasyWeChat::miniProgram();
        $data = $miniProgram->auth->session($request->code);

        if(isset($data["errcode"])){
            return $this->response->errorUnauthorized("code 不正确");
        }

        $user = User::where("weapp_openid",$data["openid"])->first();

        if($user){
            return $this->response->errorForbidden("微信已绑定其他用户，请直接登录");
        }

        $user = User::create([
            "name"=>$request->name,
            "email"=>$request->email,
            "password"=>$request->password,
            "weapp_openid"=>$data["openid"],
            "weixin_session_key"=>$data["session_key"]
        ]);

        return $this->response->item($user,new UserTransformer())->setMeta([
            "access_token" => \Auth::guard("api")->login($user),
            "token_type"=> "Bearer",
            "expires_in"=> \Auth::guard('api')->factory()->getTTL()*60
        ])->setStatusCode(201);
    }

    public function show(User $user){
        return $this->response->item($user,new UserTransformer());
    }
}
