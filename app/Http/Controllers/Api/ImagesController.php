<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function store(ImageRequest $request,ImageUploadHandler $uploadHandler){
        $user = $this->auth->user();
        $size = $request->type == "avatar"?200:1024;
        $result = $uploadHandler->save($request->image,str_plural($request->type),$user->id,$size);
        $image = new Image();
        $image->user_id = $user->id;
        $image->type = $request->type;
        $image->path = $result["path"];
        $image->save();

        return $this->response->item($image,new ImageTransformer());
    }
}
