<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class RepliesRequest extends FormRequest
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
            "content" =>"required|min:2",
            "reply_user_id"=>"exists:users,id"
        ];
    }
}
