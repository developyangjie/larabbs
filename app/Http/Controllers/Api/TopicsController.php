<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicTransformer;

use Dingo\Api\Http\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Vanry\Scout\Highlighter;

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

        $topics = $topic->withOrder($request->order)->where($where)->paginate(20);;

        return $this->response->paginator($topics,new TopicTransformer());

    }

    public function userIndex(User $user){
        $topics = $user->topices()->recent()->paginate(20);

        return $this->response->paginator($topics,new TopicTransformer());
    }

    public function show(Topic $topic){
        return $this->response->item($topic,new TopicTransformer());
    }

    public function search(Request $request){
        $q = $request->q;
        $paginator = [];
        if ($q) {
            $paginator = Topic::search($q)->paginate(10);
            $tokenizer = app('tntsearch.tokenizer')->driver();
            foreach($paginator as $post){
                $post->title = $this->highlight($tokenizer,$post->title, $q,'span');
                $post->body = $this->highlight($tokenizer,str_limit(strip_tags($post->body),200), $q,'span');
            }
        }
        return $this->response->paginator($paginator,new TopicTransformer());
    }

    protected function highlight($tokenizer,$text, $query, $tag = 'em')
    {
        $terms = $tokenizer->tokenize($query);

        $patterns = array_map(function ($term) {
            return "/{$term}/is";
        }, array_unique($terms));

        $replacement = "<{$tag} style='color:red'>\$0</{$tag}>";

        return preg_replace($patterns, $replacement, $text);
    }
}
