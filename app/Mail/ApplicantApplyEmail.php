<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicantApplyEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public function __construct($message)
    {
        //
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Employee Apply Notification Email',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.applyMailToRecruiter',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
