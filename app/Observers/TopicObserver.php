<?php

namespace App\Observers;

use App\Jobs\TranlateSlug;
use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic){
        $topic->excerpt = make_excerpt($topic->body);
        $topic->body = clean($topic->body);
    }

    public function saved(Topic $topic){
        if(!$topic->slug){
            dispatch(new TranlateSlug($topic));
    }
    }

    public function deleted(Topic $topic){
        DB::table("replies")->where("topic_id",$topic->id)->delete();
    }
}