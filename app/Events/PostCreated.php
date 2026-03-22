<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCreated implements ShouldBroadcast
{
    public $post;
    public $moderatorIds;

    public function __construct($post, $moderatorIds)
    {
        $this->post = $post;
        $this->moderatorIds = $moderatorIds;
    }

    public function broadcastOn()
    {
        return collect($this->moderatorIds)
            ->map(fn($id) => new PrivateChannel('user.' . $id))
            ->toArray();
    }

    public function broadcastAs()
    {
        return 'post.created';
    }

    public function broadcastWith()
    {
        return [
            'message' => 'New post "' . $this->post->title . '" needs approval',
            'post' => [
                'title' => $this->post->title,
                'id' => $this->post->id,
            ],
        ];
    }
}