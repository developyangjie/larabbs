<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\RepliesRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store(RepliesRequest $request,Topic $topic,Reply $reply){
        $reply->content = $request->input("content");
        $reply->topic_id = $topic->id;
        $reply->user_id = $this->auth->user()->id;
        $reply->save();

        return $this->response->item($reply,new ReplyTransformer())->setStatusCode(201);
    }

    public function destroy(Topic $topic,Reply $reply){
        if($topic->id != $reply->topic_id){
            $this->response->errorBadRequest();
        }

        $this->authorize("destroy",$reply);
        $reply->delete();
        return $this->response->noContent();
    }

    public function show(Topic $topic){
        $replies = $topic->replies()->paginate(20);
        return $this->response->paginator($replies,new ReplyTransformer());
    }

    public function userIndex(User $user){
        $replies = $user->replies()->paginate(20);

        return $this->response->paginator($replies,new ReplyTransformer());
    }
}
