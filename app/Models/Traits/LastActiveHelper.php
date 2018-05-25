<?php
/**
 * Created by PhpStorm.
 * User: yangjie
 * Date: 2018/1/23
 * Time: 14:56
 */
namespace App\Models\Traits;
use Illuminate\Support\Carbon;
use Redis;

trait LastActiveHelper{
    protected $hash_prefix = "larabbs_last_active_at_";

    protected $field_prefix = "user_";

    public function recordLastActiveTime(){
        $date = date("Y-m-d");
        $hash = $this->hash_prefix.$date;
        $field = $this->field_prefix.$this->id;

        $now = date("Y-m-d H:i:s");

        Redis::hSet($hash,$field,$now);

    }

    public function getSyncUserActivedAt(){
        $yestoday_hash = $this->hash_prefix.date("Y-m-d",time() - 86400);

        $dates = Redis::hGetAll($yestoday_hash);

        foreach($dates as $user_id => $actived_at){
            $user_id = str_replace($this->field_prefix,'',$user_id);
            if($user = $this->find($user_id)){
                $user->last_actived_at = $actived_at;
                $user->save();
            }
        }

        Redis::del($yestoday_hash);
    }

    public function getLastActivedAtAttribute($value){
        $hash = $this->hash_prefix.date("Y-m-d");

        $field = $this->field_prefix.$this->id;

        $datetime = Redis::hGet($hash,$field)?:$value;

        if($datetime){
            return new Carbon($datetime);
        }else{
            return $this->created_at;
        }
    }
}