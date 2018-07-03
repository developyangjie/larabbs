<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/6/21
 * Time: 15:40
 */
namespace App\Traits;
trait EsSearchable{
    public $searchSettings = [
        'attributesToHighlight' => [
            '*'
        ]
    ];
    public $esTag = true;

    public $espreTags = ["<span style='color:#EB5424'>"];

    public $espostTags = ["</span>"];

    public $highlight = [];
}