<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/8
 * Time: 15:38
 */

return [
    "title"=>"用户",
    "single"=>"用户",
    "model"=>\App\Models\User::class,
    "permission"=>function(){
        return \Illuminate\Support\Facades\Auth::user()->can("manage_users");
    },
    "columns"=>[
        "id",
        "avatar"=>[
            "title"=>"头像",
            'output' => function ($avatar, $model) {
                return empty($avatar) ? 'N/A' : '<img src="'.$avatar.'" width="40">';
            },
            "sortable"=>false
        ],
        "name"=>[
            "title"=>"用户名",
            "sortable"=>false,
            "output"=>function($name,$model){
                return '<a href="/users/'.$model->id.'" target="_blank">'.$name.'</a>';
            }
        ],
        "email"=>[
            "title"=>"邮箱",
        ],
        "operation"=>[
            "title"=>"管理",
            "sortable"=>false
        ],
    ],
    // 『模型表单』设置项
    'edit_fields' => [
        'name' => [
            'title' => '用户名',
        ],
        'email' => [
            'title' => '邮箱',
        ],
        'password' => [
            'title' => '密码',

            // 表单使用 input 类型 password
            'type' => 'password',
        ],
        'avatar' => [
            'title' => '用户头像',

            // 设置表单条目的类型，默认的 type 是 input
            'type' => 'image',

            // 图片上传必须设置图片存放路径
            'location' => public_path().'/uploads/imgaes/avatars/',
        ],
        'roles' => [
            'title'      => '用户角色',

            // 指定数据的类型为关联模型
            'type'       => 'relationship',

            // 关联模型的字段，用来做关联显示
            'name_field' => 'name',
        ],
    ],
    'filters' => [
        'id' => [

            // 过滤表单条目显示名称
            'title' => '用户 ID',
        ],
        'name' => [
            'title' => '用户名',
        ],
        'email' => [
            'title' => '邮箱',
        ],
    ],
];