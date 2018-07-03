<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/7/2
 * Time: 17:42
 */
namespace App\Events;

use Dingo\Api\Event\ResponseWasMorphed;

class AddMetaWeixinVersion{
    public function handle(ResponseWasMorphed $event){
        if(!isset($event->content['meta']['version'])){
            $event->response->headers->set(
                'weixin_version',
                config("api.weixin_version")
            );
        }
    }
}