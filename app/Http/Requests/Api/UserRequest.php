<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;
use Dingo\Api\Routing\Helpers;

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
        switch($this->method()){
            case "POST":
                return [
                    "name"=>"required|string|between:1,55",
                    "password"=>"required|string|min:6|max:32",
                    "verification_key"=>"required|string",
                    "verification_code"=>"required|string"
                ];
                break;
            case "PUT":
            case "PATCH":
                $user = app('Dingo\Api\Auth\Auth')->user();
                $userId = $user->id;
                return [
                    "name"=>"between:1,55|string|unique:users,name,".$userId,
                    "email"=>"email",
                    "introduction"=>"max:80",
                    "avatar_image_id"=>"exists:images,id,type,avatar,user_id,".$userId
                ];
                break;
        }
    }
}
