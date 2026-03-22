<?php

namespace App\Services;

use App\Events\PostCreated;
use App\Jobs\AutoApprovePostJob;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use App\Mail\PostStatusChangedMail;
use App\Events\PostStatusUpdated;
use App\Mail\PostCreatedMail;
use App\Models\User;

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

        $moderators = User::where('role', 'moderator')->get();

        $moderatorIds = $moderators->pluck('id')->toArray();

        event(new PostCreated($post, $moderatorIds));

        foreach ($moderators as $moderator) {
            Mail::to($moderator->email)->send(new PostCreatedMail($post));
        }

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

        $updateData = [
            'status'      => 'edited',
            'title'       => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
        ];

        if (isset($data['image'])) {
            $updateData['image'] = $data['image'];
        }

        $post->update($updateData);

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

        Mail::to($post->user->email)->send(new PostStatusChangedMail($post));

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

        Mail::to($post->user->email)->send(new PostStatusChangedMail($post));

        event(new PostStatusUpdated($post, $post->user_id));

        return $post;
    }


    public function getFiltered($request, $user = null)
    {
        return Post::query()
            ->with(['category', 'user', 'comments.replies'])
            ->withCount('comments')
            ->when(!$user, function ($q) {
                $q->where('status', 'approved');
            })
            ->when($user && !in_array($user->role, ['admin', 'moderator']), function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->when($request->filled('title'), function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->title . '%');
            })
            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })
            ->latest()
            ->get();
    }
}
