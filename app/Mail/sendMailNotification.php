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

    public $time ;
    public $date;
    public $description;
    public $address;
    public $applicant_name;
    public $zoom_link;
    public $jobName;
    public $company_email;

    public $company_name;

    public function __construct($applicant_name,$jobName, $address, $date, $time, $description, $zoom_link, $company_email, $company_name)
    {
        $this->applicant_name = $applicant_name;
        $this->jobName = $jobName;
        $this->address = $address ?? null;
        $this->date = $date ?? null;
        $this->time = $time ?? null;
        $this->description = $description;
        $this->zoom_link = $zoom_link ?? null;
        $this->company_email = $company_email;
        $this->company_name = $company_name;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Interview Invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.sendMail',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
