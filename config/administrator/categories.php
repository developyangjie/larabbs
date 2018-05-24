<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/18
 * Time: 13:42
 */
return [
    "title"=>"分类",
    "single"=>"分类",
    "model"=>\App\Models\Category::class,
    "action_permissions"=>[
        "delete"=>function(){
            return \Illuminate\Support\Facades\Auth::user()->hasRole("Founder");
        }
    ],
    "columns"=>[
        "id"=>[
            "title"=>"ID"
        ],
        "name"=>[
            "title"=>"名称",
            "sortable"=>false
        ],
        "description"=>[
            "title"=>"描述",
            "sortable"=>false
        ],
        "operation"=>[
            "title"=>"管理",
            "sortable"=>false
        ]
    ],
    "edit_fields"=>[
        "name"=>[
            "title"=>"名称"
        ],
        "description"=>[
            "title"=>"描述",
            "type"=>"textarea"
        ]
    ],
    "filters"=>[
        "id"=>[
            "title"=>"分类 ID",
        ],
        "name"=>[
            "title"=>"名称"
        ],
        "description"=>[
            "title"=>"描述"
        ]
    ],
    "rules"=>[
        "name"=>"required|min:1|unique:categories"
    ],
    "messages"=>[
        "name.unique"=>"分类名在数据库有重复，请选用其他名称",
        "name.min"=>"请保持一个字符以上"
    ]

];