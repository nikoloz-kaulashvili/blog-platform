<?php

namespace App\Services;

use App\Jobs\AutoApprovePostJob;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostStatusChangedMail;
use App\Events\PostStatusUpdated;

class PostService
{
    public function create(array $data): Post
    {
        if (isset($data['image'])) {
            $data['image'] = $data['image']->store('posts', 'public');
        }

        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        $post = Post::create($data);

        dispatch(new AutoApprovePostJob($post->id))
            ->delay(now()->addHours(2));

        return $post;
    }

    public function update(Post $post, array $data): Post
    {
        if (isset($data['image'])) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }

            $data['image'] = $data['image']->store('posts', 'public');
        }

        $post->update([
            'title'       => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
        ]);

        return $post;
    }

    public function delete(Post $post): void
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
    }

    public function approve(Post $post): Post
    {
        $post->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        app(NotificationService::class)->send(
            $post->user_id,
            'პოსტი:' . $post->title . ', დადასტურებულია',
            json_encode([
                'post_id' => $post->id,
                'status'  => $post->status
            ])
        );

        Mail::to($post->user->email)
            ->send(new PostStatusChangedMail($post));
        event(new PostStatusUpdated($post, $post->user_id));
        return $post;
    }

    public function reject(Post $post): Post
    {
        $post->update([
            'status' => 'rejected',
        ]);
        app(NotificationService::class)->send(
            $post->user_id,
            'პოსტი:' . $post->title . ', უარყოფილია',
            json_encode([
                'post_id' => $post->id,
                'status'  => $post->status
            ])
        );

        Mail::to($post->user->email)
            ->send(new PostStatusChangedMail($post));
        event(new PostStatusUpdated($post, $post->user_id));
        return $post;
    }

    public function setStatus(Post $post, string $status): Post
    {
        $data = ['status' => $status];

        if ($status === 'approved') {
            $data['approved_at'] = now();
        }

        if ($status !== 'approved') {
            $data['approved_at'] = null;
        }

        $post->update($data);

        return $post;
    }

    public function getApproved()
    {
        return Post::with(['user', 'category'])
            ->where('status', 'approved')
            ->latest()
            ->get();
    }

    public function getAll()
    {
        return Post::latest()->get();
    }

    public function getFiltered($request, $user)
    {
        $query = Post::with('category')->latest();

        if ($user->role === 'user') {
            $query->where('status', 'approved');
        }

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        return $query->get();
    }
}
