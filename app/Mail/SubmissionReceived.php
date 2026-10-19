<?php

namespace App\Mail;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionReceived extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Submission $submission)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[neopharmlabs.com] ' . $this->submission->title,
            replyTo: $this->submission->email ? [$this->submission->email] : [],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'mail.submission');
    }
}
