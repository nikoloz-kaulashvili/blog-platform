<?php

namespace App\Mail;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PostCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Post Needs Approval'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.post-created',
            with: [
                'post' => $this->post,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}