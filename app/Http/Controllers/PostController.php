<?php

namespace App\Http\Controllers;

use App\Handlers\WechatPostSpider;
use App\Models\Post;
use App\Models\Topic;
use Goutte\Client;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function search(Request $request){

        $q = $request->get('q');
        $paginator = [];
        if ($q) {
            $paginator = Topic::search($q)->paginate();
        }
        return view('search', compact('paginator', 'q'));
    }
}
