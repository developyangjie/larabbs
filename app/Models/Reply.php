<?php

namespace App\Models;

/**
 * App\Models\Reply
 *
 * @property int $id
 * @property int $topic_id
 * @property int $user_id
 * @property string $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Topic $topic
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereUserId($value)
 * @mixin \Eloquent
 * @property int $reply_user_id
 * @property string|null $reply_user_name
 * @property int $reply_replied_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereReplyRepliedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereReplyUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Reply whereReplyUserName($value)
 */
class Reply extends Model
{
    protected $fillable = [ 'content','reply_user_id','reply_user_name'];

    public function topic(){
        return $this->belongsTo(Topic::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('id',"desc");
    }
}
