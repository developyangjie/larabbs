<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;
class NotificationsController extends Controller
{
    public function stats(){
        return $this->response->array(
            [
                "unread_count" => $this->auth->user()->notification_count
            ]
        );
    }

    public function index(){
        $notifications = $this->auth->user()->notifications()->paginate(20);
        return $this->response->paginator($notifications,new NotificationTransformer());
    }

    public function read(){
        $this->auth->user()->markAsRead();
        $this->response->noContent();
    }
}
