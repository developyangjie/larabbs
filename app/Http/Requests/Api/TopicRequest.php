<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest;

class TopicRequest extends FormRequest
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
        $rules = [];
        switch($this->method()){
            case "POST":
                $rules = [
                    "title" => "required|string",
                    "body" =>"required|string",
                    "category_id" =>"required|exists:categories,id"
                ];
                break;
            case "PATCH":
                $rules = [
                    "title" => "string",
                    "body" =>"string",
                    "category_id" =>"exists:categories,id"
                ];
                break;
        }
        return $rules;
    }

    public function attributes()
    {
        return [
            "title"=>"标题",
            "body"=>"内容",
            "category_id"=>"分类"
        ];
    }
}
