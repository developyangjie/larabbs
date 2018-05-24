<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2017/12/26
 * Time: 15:25
 */
namespace App\Handlers;
class SlugTranslateHandler{
    public function translate($text){
        $http = new \GuzzleHttp\Client();
        $api = "http://api.fanyi.baidu.com/api/trans/vip/translate?";
        $appid = config("services.baidu_translate.appid");
        $key = config("services.baidu_translate.key");
        $salt = time();

        if(empty($appid) || empty($key)){
            return $this->pinyin($text);
        }

        $sign = md5($appid.$text.$salt.$key);

        $query = http_build_query([
            "q"=>$text,
            "from"=>'zh',
            "to"=>"en",
            "appid"=>$appid,
            "salt"=>$salt,
            "sign"=>$sign
        ]);

        $response = $http->get($api.$query);
        $result = \GuzzleHttp\json_decode($response->getBody(),true);
        if(isset($result['trans_result'][0]['dst'])){
            return str_slug($result['trans_result'][0]['dst']);
        }else{
            return $this->pinyin($text);
        }

    }

    public function pinyin($text){
        $pinyin = new \Overtrue\Pinyin\Pinyin();
        return str_slug($pinyin->permalink($text));
    }
}