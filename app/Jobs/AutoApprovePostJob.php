<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\NotificationService;

class AutoApprovePostJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(public int $postId) {}

    public function handle()
    {
        $post = Post::find($this->postId);

        if (!$post) {
            return;
        }

        if ($post->status === 'pending') {
            $post->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

            app(NotificationService::class)->send(
                $post->user_id,
                'პოსტი:' . $post->title . ', დადასტურებულია',
                json_encode([
                    'post_id' => $post->id
                ])
            );
        }
    }
}
