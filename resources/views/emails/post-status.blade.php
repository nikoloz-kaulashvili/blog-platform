<h2>Your post status has been updated</h2>

<p><strong>Title:</strong> {{ $post->title }}</p>

<p>
    Status:
    @if($post->status === 'approved')
        Approved ✅
    @else
        Rejected ❌
    @endif
</p>