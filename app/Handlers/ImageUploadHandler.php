<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2017/12/8
 * Time: 14:31
 */
namespace App\Handlers;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;
use Mockery\Matcher\Closure;

class ImageUploadHandler{
    protected $allow_ext = ['png','gif','jpg','jpeg'];

    public function save(UploadedFile $file,$foloder,$file_prefix = "",$max = false){
        $foloder_name = 'uploads' . DIRECTORY_SEPARATOR . 'imgaes' . DIRECTORY_SEPARATOR ;
        if(!empty($foloder)){
            $foloder_name .= $foloder.DIRECTORY_SEPARATOR;
        }
        $foloder_name .= date("Ym").DIRECTORY_SEPARATOR.date("d").DIRECTORY_SEPARATOR;

        $upload_path = public_path().DIRECTORY_SEPARATOR.$foloder_name;

        $ext = strtolower($file->getClientOriginalExtension()?:'png');
        $file_name = time()."_".str_random(5).".".$ext;
        if($file_prefix){
            $file_name = $file_prefix."_".$file_name;
        }

        if(!in_array($ext,$this->allow_ext)){
            return false;
        }

        $file->move($upload_path,$file_name);
        if($max && $ext != "gif"){
            $this->reduceSize($upload_path.DIRECTORY_SEPARATOR.$file_name,$max);
        }
        return [
            "path"=>config("app.url")."/$foloder_name/$file_name"
        ];
    }

    public function reduceSize($path,$size){
        Image::make($path)->resize($size,$size,function(Constraint $constraint){
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save();
    }
}