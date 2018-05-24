<?php
namespace App\Listeners;

class UserEventSubscriber
{
    public function onTest($event) {

        \Log::info("中午吃点什么呢");
    }

    /**
     * 为订阅者注册监听器。
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Controller\HomeController',
            'App\Listeners\UserEventSubscriber@onTest'
        );
    }

}