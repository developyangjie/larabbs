<?php

namespace App\Notifications;

use App\Models\Reply;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TopicReplied extends Notification implements ShouldQueue
{
    use Queueable;
    public $reply;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toDatabase($notifiable)
    {
        $topic = $this->reply->topic;
        $link =  $topic->link(['#reply' . $this->reply->id]);
        $reply_user_id = 0;
        $reply_user_name = "";
        $reply_tags_content = "";
        if($this->reply->reply_user_id){
            $user = User::find($this->reply->reply_user_id);
            $reply_user_id = $user->id;
            $reply_user_name = $user->name;
            $repliedReply = Reply::find($this->reply->reply_replied_id);
            $reply_tags_content = $repliedReply->content;
        }

        return [
            'reply_id' => $this->reply->id,
            'reply_content' => $this->reply->content,
            'reply_tags_content'=>strip_tags($reply_tags_content),
            'user_id' => $this->reply->user->id,
            'user_name' => $this->reply->user->name,
            'user_avatar' => $this->reply->user->avatar,
            'topic_link' => $link,
            'topic_id' => $topic->id,
            'topic_title' => $topic->title,
            'reply_user_id'=>$reply_user_id,
            'reply_user_name' =>$reply_user_name,
        ];
    }

    public function toMail($notifiable){
        $link =  $this->reply->topic->link(['#reply' . $this->reply->id]);
        return (new MailMessage)->line("您有新回复")->action("查看回复",$link);
    }
}
