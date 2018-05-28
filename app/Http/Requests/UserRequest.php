<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>'required|max:255|unique:users,name,'.Auth::id(),
            "email"=>"required|email",
            "introduction"=>"max:80",
            "avatar"=>"mimes:jpeg,bmp,png,gif,jpg|dimensions:min_width=300,min_height=300"
        ];
    }

    public function messages(){
        return [
            "avatar.mimes"=>"图片格式不正确",
            "avatar.dimensions"=>"图片不清晰，请换大图"
        ];
    }
}
