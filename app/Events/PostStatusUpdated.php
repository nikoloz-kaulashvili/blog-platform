<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostStatusUpdated implements ShouldBroadcast
{
    public $post;
    public $userId;

    public function __construct($post, $userId)
    {
        $this->post = $post;
        $this->userId = $userId;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'post.status.updated';
    }
}
