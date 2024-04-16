<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecruiterNotification extends Notification
{
    use Queueable;

    public $application;
    // public $description;

    public function __construct($application)
    {
        $this->application = $application;
        // $this->description = $description;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'application' => $this->application,
           // 'message' => $this->message,
            // 'description' => $this->description
        ];
    }
}
