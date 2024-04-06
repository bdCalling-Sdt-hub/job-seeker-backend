<?php

namespace App\Jobs;

use App\Mail\ReportMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ReportMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sendReportMail;
    public $subject;
    public $message;
    public function __construct($sendReportMail,$subject,$message)
    {
        $this->sendReportMail = $sendReportMail;
        $this->subject = $subject;
        $this->message = $message;
    }

    public function handle(): void
    {
        $mail = $this->sendReportMail;
        $subject = $this->subject;
        $message = $this->message;
        $email = new ReportMail($mail,$subject,$message);
        Mail::to($this->sendReportMail)->send($email);
    }
}
