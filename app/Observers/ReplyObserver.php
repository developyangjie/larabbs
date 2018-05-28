<?php

namespace App\Observers;

use App\Models\Reply;
use App\Models\User;
use App\Notifications\ReplyAt;
use App\Notifications\TopicReplied;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content);
    }

    public function created(Reply $reply){
        $reply->topic->increment('reply_count',1);

        if($reply->user->id !== $reply->topic->user->id || ($reply->reply_user_id && $reply->reply_user_id !== $reply->user->id)){
            if($reply->reply_user_id){
                $replyUser = User::find($reply->reply_user_id);
                $replyUser->increment("notification_count",1);
                $replyUser->notify(new TopicReplied($reply));
            }else{
                $reply->topic->user->increment("notification_count",1);
                $reply->topic->user->notify(new TopicReplied($reply));
            }

            preg_match_all('/@(\w+)\s+/',$reply->content,$matchs);

            $replyAt = $matchs[1];
            if(count($replyAt) > 0){
                $users = User::whereIn('name',$replyAt)->get();
                foreach($users as $user){
                    if($user->id !== $reply->user->id){
                        $user->notify(new ReplyAt($reply));
                    }
                }
            }
        }
    }

    public function deleted(Reply $reply){
        DB::table("topics")->where("id",$reply->topic_id)->decrement("reply_count",1);
    }
}