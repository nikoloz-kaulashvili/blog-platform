<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Mail\Mailable;

class PostStatusChangedMail extends Mailable
{
    public function __construct(public Post $post) {}

    public function build()
    {
        return $this->subject('Post Status Updated')
            ->view('emails.post-status');
    }
}