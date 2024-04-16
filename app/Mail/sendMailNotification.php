<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendMailNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $time = '';
    public $date = '';
    public $description = '';
    public $address = '';
    public $jobName = '';

    public function __construct($time, $date, $description, $address, $jobName)
    {
        $this->time = $time;
        $this->date = $date;
        $this->description = $description;
        $this->address = $address;
        $this->jobName = $jobName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Mail Applications',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.sendMail',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
