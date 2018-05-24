<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodesRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\SocialiteServiceProvider;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodesRequest $request,EasySms $easySms){
        $captcha = \Cache::get($request->captcha_key);
        if(!$captcha){
            $this->response->error("图片验证码已过期",422);
        }

        if(!hash_equals((string)$captcha['code'],$request->captcha_code)){
            $this->response->error("图片验证码错误",401);
        }
        $phone = $captcha['phone'];
        $code = "";
        if(!app()->environment("production")){
           $code = '1234';
        }else{
           $code = random_int(1000,9999);
           try{
               $result = $easySms->send($phone,[
                   "content"=>"您的验证码是".$code
               ]);
           }catch (\Exception  $exception){
               return $this->response->errorInternal($exception->getMessage() ?: '短信发送异常');
           }
        }

        $key = 'verificationCode_'.str_random(15);

        $expiredAt = Carbon::now()->addMinutes(10);

        \Cache::put($key,['phone'=>$phone,'code'=>$code],$expiredAt);

        return $this->response->array([
            "key"=>$key,
            "expired_at"=>$expiredAt->toDateTimeString()
        ])->setStatusCode(201);
    }
}
