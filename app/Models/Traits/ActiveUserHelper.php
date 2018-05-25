<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/22
 * Time: 14:14
 */
namespace App\Models\Traits;
trait ActiveUserHelper{

    protected $users = [];
    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 7;
    protected $user_number = 6;

    protected $cache_key = "larabbs_active_users";
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers(){
        return \Cache::remember($this->cache_key,$this->cache_expire_in_minutes,function(){
            return $this->calculateActiveUsers();
        });
    }

    public function calculateActiveUsers(){
        $this->calculateTopicScore();
        $this->calculateReplyScore();

        $users = array_sort($this->users,function($user){
            return $user["score"];
        });

        $users = array_reverse($users,true);

        $users = array_slice($users,0,$this->user_number,true);

        $active_collect = collect();

        foreach($users as $user_id => $user){
            $user = $this->find($user_id);
            if($user){
                $active_collect->push($user);
            }
        }

        return $active_collect;
    }

    public function calculateTopicScore(){
        $topic_users = \App\Models\Topic::query()->select(\DB::raw('user_id,count(*) as topic_count'))
            ->where("created_at",">=",date("Y-m-d H:i:s",time() - $this->pass_days*86400))
            ->groupBy("user_id")
            ->get();

        foreach($topic_users as $value){
            $this->users[$value->user_id]["score"] = $value->topic_count * $this->topic_weight;
        }
    }

    public function calculateReplyScore(){
        $reply_users = \App\Models\Reply::query()->select(\DB::raw('user_id,count(*) as reply_count'))
            ->where("created_at",">=",date("Y-m-d H:i:s",time() - $this->pass_days*86400))
            ->groupBy("user_id")
            ->get();

        foreach($reply_users as $value){
            $reply_score = $value->reply_count * $this->reply_weight;
            if(isset($this->users[$value->user_id])){
                $this->users[$value->user_id]["score"] += $reply_score;
            }else{
                $this->users[$value->user_id]["score"] = $reply_score;
            }
        }
    }

    public function cacheActiveUsers($active_users){
        \Cache::put($this->cache_key,$active_users,$this->cache_expire_in_minutes);
    }

    public function calculateAndCacheActiveUsers(){
        $active_users = $this->calculateActiveUsers();

        $this->cacheActiveUsers($active_users);
    }
}