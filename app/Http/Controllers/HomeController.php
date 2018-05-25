<?php

namespace App\Http\Controllers;

use App\Events\ShippingStatusUpdated;
use App\Http\Resources\UserResource;
use App\Mail\OrderShipped;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {
//        echo $request->path()."\n";
//        echo $request->url()."\n";
//        echo $request->fullUrl()."\n";
//        echo $request->query('name', 'Sally');
//        if($request->has("id")){
//            echo "id exist";
//        }
//
//        if($request->filled("id")){
//            echo "id exist and not null";
//
//        }
//
//        echo base_path('public/uploads')."/imgaes/avatars/bMAXIExmhMdVgcMspsoK8WOIy2dyCBrS.png";
     //   return response()->file(base_path('public/uploads')."/imgaes/avatars/201803/21/11_1521622436_1bB8p.jpg");
        echo 1;
    }

    public function broadcast(){
        \Log::info("中午吃点什么呢");
        $topic = Topic::find(4);
      //  Redis::set("111",1);
      //  echo Redis::get("111");
        //event(new ShippingStatusUpdated($topic));
//        $collection = collect(['taylor', 'abigail', null])->map(function ($name) {
//            return strtoupper($name);
//        })
//            ->reject(function ($name) {
//                return empty($name);
//            });
//        var_dump($collection->all());
      //  var_dump((array)\DB::table("users")->get()->all());
//        $collection = collect([1, 8, 3, 4, 5]);
//
//        $chunk = $collection->splice(4);
//        var_dump($chunk);

//        $path = app_path();
//        echo $path;
//        $path = app_path('Http/Controllers/Controller.php');
//        echo $path;

//        $camel = camel_case('foo_bar');
//        echo $camel;

//        $class = class_basename(User::class);
//        echo kebab_case('foo_bar');

      //  info('User login attempt failed.', ['id' => 1]);
      //  \Mail::to("875241784@qq.com")->queue(new OrderShipped(Topic::find(1)));
//        $randomUser = \DB::table('users')
//            ->inRandomOrder()
//            ->first();
//        var_dump($randomUser);

        return new UserResource(User::find(1));
       // return UserResource::collection(User::paginate());
    }

    public function broadcastShow(){
        return view("home.broadcast");
    }
}
