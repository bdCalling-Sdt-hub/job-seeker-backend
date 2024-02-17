<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    //

    public function testNotification()
    {

        $message = 'this is all about message';
        $time = '10.00pm';
        $data = auth()->user();
        Notification::send($data, new UserNotification($message, $time, $data));
        return response()->json([
            'data' => 'notification add successfully',
        ]);
    }
}
