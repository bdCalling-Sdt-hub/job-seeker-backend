<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $time;
    protected $user;
    public function __construct($message,$time,$user)
    {
        //
        $this->message = $message;
        $this->time = $time;
        $this->user = $user;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'message' => $this->message,
            'time' => $this->time,
            'user' => $this->user,
        ];
    }
}
