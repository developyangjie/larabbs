<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/23
 * Time: 13:55
 */
namespace App\Observers;

use App\Models\Link;

class LinkObserver{
    public function saved(Link $link){
        \Cache::forget($link->cache_key);
    }
}