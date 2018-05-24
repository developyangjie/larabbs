<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\WeappUserRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth",[
           'except'=>[
               'show'
           ]
        ]);
    }

    public function show(User $user){
        return view("users.show",compact('user'));
    }

    public function edit(User $user){
        return view("users.edit",compact("user"));
    }

    public function update(UserRequest $request,User $user){
        $this->authorize("update",$user);
        $data = $request->all();
        if($request->avatar){
            $upload = new ImageUploadHandler();
            $result = $upload->save($request->file("avatar"),"avatars",$user->id,200);
            if($result){
                $data["avatar"] = $result["path"];
            }
        }
        $rst = $user->update($data);
        if(false !== $rst){
            return redirect()->route("users.show",$user->id)->with("success","更新成功");
        }else{
            return redirect()->back()->with("danger","更新失败");
        }
    }
}
