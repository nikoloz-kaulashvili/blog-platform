<h2>New post needs approval</h2>

<p><strong>Title:</strong> {{ $post->title }}</p>

<p><strong>Description:</strong> {{ $post->description }}</p>

<p><strong>Author:</strong> {{ $post->user->name ?? 'User' }}</p>

<p>
    Status:
    @if($post->status === 'pending')
        Pending ⏳
    @endif
</p>