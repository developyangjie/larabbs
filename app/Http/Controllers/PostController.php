<?php

namespace App\Http\Controllers;

use App\Handlers\WechatPostSpider;
use App\Models\Post;
use App\Models\Topic;
use Goutte\Client;
use Illuminate\Http\Request;
use Vanry\Scout\Highlighter;

class PostController extends Controller
{
    public function search(Request $request){

        $q = $request->get('q');
        $paginator = [];
        if ($q) {
            $paginator = Topic::search($q)->paginate();
            $tokenizer = app('tntsearch.tokenizer')->driver();
            $highlighter = new Highlighter($tokenizer);
            foreach($paginator as $post){
                $post->title = $highlighter->highlight($post->title, $q,'span');
                $post->body = $highlighter->highlight(str_limit($post->body,500), $q,'span');
            }
        }
        return view('search', compact('paginator', 'q'));
    }
}
