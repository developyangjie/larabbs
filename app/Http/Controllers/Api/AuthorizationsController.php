<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Http\Requests\Api\WeappAuthorizationRequest;
use App\Models\User;
use App\Traits\PassportToken;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response;

class AuthorizationsController extends Controller
{
    use PassportToken;
    public function socialStore($type,SocialAuthorizationRequest $request){
        if(!in_array($type,['weixin'])){
            return $this->response->error("参数错误",404);
        }

        $driver = Socialite::driver($type);
        try{
            if($request->code){
                $response = $driver->getAccessTokenResponse($request->code);
                $token = $response["access_token"];
            }else{
                $token = $request->access_token;
                if($this->type == "weixin"){
                    $driver->setOpenId($request->open_id);
                }
            }

            $socialUser = $driver->userFromToken($token);
        }catch (\Exception $e){
            return $this->response->error("参数错误",401);
        }

        switch($type){
            case "weixin":
                $unionid = isset($socialUser->user["unionid"])?$socialUser->user["unionid"]:null;
                if($unionid){
                    $user = User::where("weixin_unionid",$unionid)->first();
                }else{
                    $user = User::where("weixin_openid",$socialUser->getId())->first();
                }
                if(!$user){
                    $user = User::create([
                        "name"=>$socialUser->getNickName(),
                        "weixin_unionid"=>$unionid,
                        "weixin_openid"=>$socialUser->getId(),
                        "avatar"=>$socialUser->getAvatar()
                    ]);
                }
                break;
        }

        $token = \Auth::guard("api")->login($user);

        return $this->respondWithToken($token);
    }

    public function store(AuthorizationRequest $request){
        $credentials  = [];
        if(filter_var($request->username,FILTER_VALIDATE_EMAIL)){
            $credentials["email"] = $request->username;
        }else{
            $credentials["phone"] = $request->username;
        }

        $credentials["password"] = $request->password;
        if(!$token = \Auth::guard("api")->attempt($credentials)){
            return $this->response->error("参数错误",401);
        }

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token){
        return $this->response->array(
            [
                "access_token"=>$token,
                "token_type"=>"bearer",
                "expires_in"=>\Auth::guard("api")->factory()->getTTL()*60
            ]
        )->setStatusCode(201);
    }

    public function logout(){
        \Auth::guard("api")->logout();
        return $this->response->noContent();
    }

    public function refresh(){
        return $this->respondWithToken(\Auth::guard("api")->refresh());
    }

    public function passportStore(AuthorizationRequest $request,AuthorizationServer $server,ServerRequestInterface $serverRequest){
        try{
            return $server->respondToAccessTokenRequest($serverRequest,new Response())->withStatus(201);
        }catch (OAuthServerException $e){
            $this->response->errorUnauthorized($e->getMessage());
        }

    }

    public function passportLogin(){
        $user = User::find(1);
        $result = $this->getBearerTokenByUser($user,1,false);
        return $this->response->array($result)->setStatusCode(201);
    }

    public function weappStore(WeappAuthorizationRequest $request){
        $code = $request->code;
        $miniprogram = \EasyWeChat::miniprogram();
        $data = $miniprogram->auth->session($code);

        if(isset($data["errcode"])){
            return $this->response->errorUnauthorized("code 不正确");
        }

        $user = User::where("weapp_openid",$data["openid"])->first();

        $attributes["weixin_session_key"] = $data["session_key"];

        if(!$user){
            if(!$request->username){
                return $this->response->errorForbidden("用户名不存在");
            }

            $username = $request->username;
            $conditions = [];
            if(filter_var($username,FILTER_VALIDATE_EMAIL)){
                $credentials["email"] = $username;
                $conditions[] = ["email","=",$username];
            }else{
                $credentials["phone"]  = $username;
                $conditions[] = ["phone","=",$username];
            }

            $credentials["password"] = $request->password;
            if(!\Auth::guard("api")->once($credentials)){
                return $this->response->errorUnauthorized("用户名或者密码错误");
            }
            $user = User::where($conditions)->first();
            $attributes["weapp_openid"] = $data["openid"];
        }
        $user->update($attributes);
        $token = \Auth::guard("api")->login($user);
        return $this->respondWithToken($token)->setStatusCode(201);
    }
}
