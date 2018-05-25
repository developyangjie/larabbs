<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;

use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(TopicRequest $request,Topic $topic){
        $topic->fill($request->all());
        $topic->user_id = $this->auth->user()->id;
        $topic->save();

        return $this->response->item($topic,new TopicTransformer());
    }

    public function update(TopicRequest $request,Topic $topic){
        $this->authorize("update",$topic);
        $topic->update($request->all());
        return $this->response->item($topic,new TopicTransformer());
    }

    public function destroy(Topic $topic){
        $this->authorize("destroy",$topic);
        $topic->delete();
        return $this->response->noContent();
    }

    public function index(Request $request,Topic $topic){

        $where = [];
        if($categoryId  = $request->category_id){
            $where[] = ["category_id","=",$categoryId];
        }

        switch($request->order){
            case "recent":
                $topic->recent();
                break;
            default:
                $topic->recentReplied();
                break;
        }

        $topics = $topic->where($where)->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());

    }

    public function userIndex(User $user){
        $topics = $user->topices()->recent()->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());
    }

    public function show(Topic $topic){
        return $this->response->item($topic,new TopicTransformer());
    }
}
