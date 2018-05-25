<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
class CategoriesController extends Controller
{
    public function show(Category $category,Request $request){
        $topics = Topic::with("user",'category')->where('category_id',$category->id)
            ->withOrder($request->order)
            ->paginate(10);
        return view("topics.index",compact("topics","category"));
    }
}
