<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData,$subject,$message;
    public function __construct($mailData,$subject,$message)
    {
        //
        $this->mailData = $mailData;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Report Employer',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reportEmail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
