<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApplyApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject = '';
    public $description = '';

    public function __construct($subject, $description)
    {
        $this->subject = $subject;
        $this->description = $description;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Job Application',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.applyMail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
