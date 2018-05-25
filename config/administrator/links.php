<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/23
 * Time: 11:28
 */

return [
    "title"=>"资源推荐",
    "single"=>"资源推荐",
    "model"=>\App\Models\Link::class,
    "permission"=>function(){
        return Auth::user()->hasRole("Founder");
    },

    "columns"=>[
        "id"=>[
            "titkle"=>"ID"
        ],
        "title"=>[
            "title"=>"名称",
            "sortable"=>false
        ],
        "link"=>[
            "title"=>"链接",
            "sortable"=>false
        ],
        "operation"=>[
            "title"=>"操作",
            "sortable"=>false
        ]
    ],
    "edit_fields"=>[
        "title"=>[
            "title"=>"名称",
        ],
        "link"=>[
            "title"=>"链接"
        ]
    ],
    "filters"=>[
        "id"=>[
            "title"=>"标签 ID",
        ],
        "title"=>[
            "title"=>"名称"
        ]
    ],
    "rules"=>[
        "title"=>"required|min:3",
        "link"=>"required|url"
    ],
    "messages"=>[
        "title.required"=>"链接标题不能为空",
        "title.min"=>"链接标题不能少于三个字符",
        "link.url"=>"不是有效的链接地址"
    ]

];