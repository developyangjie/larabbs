<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CaptchaController extends Controller
{
    public function store(CaptchaRequest $request,CaptchaBuilder $captchaBuilder){
        $captcha_key = "captcha-".str_random(15);
        $phone = $request->phone;
        $captcha = $captchaBuilder->build();

        $expireAt = Carbon::now()->addMinutes(2);
        \Cache::put($captcha_key,["phone"=>$phone,"code"=>$captcha->getPhrase()],$expireAt);

        return $this->response->array([
            "captcha_key"=>$captcha_key,
            "expire_at"=>$expireAt->toDateTimeString(),
            "captcha_image"=>$captcha->inline()
        ])->setStatusCode(201);
    }
}
