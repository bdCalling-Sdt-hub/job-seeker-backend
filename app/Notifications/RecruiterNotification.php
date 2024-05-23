<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecruiterNotification extends Notification
{
    use Queueable;

    public $message,$time,$name,$user;

    public function __construct($message,$time,$name,$user)
    {
        $this->message = $message;
        $this->time = $time;
        $this->name = $name;
        $this->user = $user;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'message' => $this->message,
            'time' => $this->time,
            'name'=>$this->name,
            'user' => $this->user,
        ];
    }
}
