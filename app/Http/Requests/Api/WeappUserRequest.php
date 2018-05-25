<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class WeappUserRequest extends FormRequest
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
        switch($this->method()){
            case "POST":
                return [
                    "name"=>"between:3,25|regex:/^[a-zA-Z0-9\-\_]+$/|unique:users,name",
                    "password"=>"required|string|min:6|max:32",
                    "email"=>"required|email|unique:users,email"
                ];
                break;
            case "PATCH":
                $user = app('Dingo\Api\Auth\Auth')->user();
                $userId = $user->id;
                return [
                    "name"=>"between:3,25|regex:/^[a-zA-Z0-9\-\_]+$/|unique:users,name,".$userId,
                    "email"=>"email",
                    "introduction"=>"max:80",
                    "avatar_image_id"=>"exists:images,id,type,avatar,user_id,".$userId
                ];
                break;
        }
    }
}
