<?php

namespace App\Events;

use App\Models\Topic;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ShippingStatusUpdated implements ShouldBroadcast{

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */

    public $topic;

    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    public function broadcastOn()
    {
        // TODO: Implement broadcastOn() method.
        return new PrivateChannel("topic.".$this->topic->id);
    }
}