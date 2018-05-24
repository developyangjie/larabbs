<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/18
 * Time: 13:58
 */
return [
    "title"=>"话题",
    "single"=>"话题",
    "model"=>\App\Models\Topic::class,

    "columns"=>[
        "id"=>[
            "title"=>"ID"
        ],
        "title"=>[
            "title"=>"话题"
        ],
        "user"=>[
            "title"=>"作者",
            "sortable"=>false,
            "output"=>function($value,$model){
                $avatar = $model->user->avatar;
                $value = empty($avatar)?"N/A":'<img src="'.$avatar.'" style="height:22px;width:22px">'.$model->user->name;
                return '<a href="'.route("users.show",$model->user->id).'" target="_blank">'.$value.'</a>';
            }
        ],
        "category"=>[
            "title"=>"分类",
            "sortable"=>false,
            "output"=>function($value,$model){
                return model_admin_link($model->category->name,$model->category);
            }
        ],
        "reply_count"=>[
            "title"=>"评论"
        ],
        "operation"=>[
            "title"=>"管理",
            "sortable"=>false
        ]
    ],
    "edit_fields"=>[
        "title"=>[
            "title"=>"标题",
        ],
        "user"=>[
            "title"=>"用户",
            "type"=>"relationship",
            "name_field"=>"name",
            "autocomplete"=>true,
            "search_fields"=>["CONCAT(id,'',name)"],
            "options_sort_field"=>"id"
        ],
        "category"=>[
            "title"=>"分类",
            "type"=>"relationship",
            "name_field"=>"name",
            "search_field" =>["CONCAT(id,'',name)"],
            "options_sort_field"=>"id"
        ],
        "reply_count"=>[
            "title"=>"评论"
        ],
        "view_count"=>[
            "title"=>"查看"
        ]
    ],
    "filters"=>[
        "id"=>[
            "title"=>"内容 ID",
        ],
        "user"=>[
            "title"=>"用户",
            "type"=>"relationship",
            "name_field"=>"name",
            "search_fields"=>["CONCAT(id,'',name)"],
            "options_sort_field"=>"id"
        ],
        "category"=>[
            "title"=>"分类",
            "type"=>"relationship",
            "name_field"=>"name",
            "search_fields"=>["CONCAT(id,'',name)"],
            "options_sort_field"=>'id'
        ]
    ],
    "rules"=>[
        "title"=>"required"
    ]
];