<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Notifications\AdminNotification;
use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use DB;

class NotificationController extends Controller
{
    //

    public function guard()
    {
        return Auth::guard('api');
    }


    function sendNotification($message = null, $time = null, $data = null)
    {
        try {
            Notification::send($data, new UserNotification($message, $time, $data));
            return response()->json([
                'success' => true,
                'msg' => 'Notification Added',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    function sendAdminNotification($message = null, $time = null, $data = null)
    {
        try {
            Notification::send($data, new AdminNotification($message, $time, $data));
            return response()->json([
                'success' => true,
                'msg' => 'Notification Added',
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function markRead()
    {
        $user = $this->guard()->user();

        if ($user) {
            $notifications = $user->unreadNotifications()->orderBy('created_at', 'desc')->get();

            return response()->json([
                'status' => 'success',
                'notifications' => $notifications,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
    }


    public function readNotification()
    {
        $user = $this->guard()->user();

        if ($user) {
            // Mark all unread notifications as read
            $user->unreadNotifications->markAsRead();

            // Retrieve and return the updated notifications
            $notifications = $user->notifications;

            return response()->json([
                'status' => 'success',
                'notifications' => $notifications,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
    }

//    public function adminNotification(){
//
//        $notifications = DB::table('notifications')
//            ->where('notifiable_type', Story::class)
//            ->where('read_at', null)
//            ->where('type', 'App\\Notifications\\AdminNotification')
//            ->orderBy('created_at', 'desc')
//            ->get();
//
//        $formatted_notification = $notifications->map(function($notification) {
//            $notification->data = json_decode($notification->data);
//            return $notification;
//        });
//
//        if ($notifications){
//            return response()->json([
//                'message' => 'Notification list is given below',
//                'data' => $formatted_notification
//            ]);
//        }
//
//    }

    public function adminNotification(){

        $notifications = DB::table('notifications')
            ->join('users', 'notifications.data->user->user_id', '=', 'users.id')
            ->where('notifications.notifiable_type', Story::class)
            ->where('notifications.read_at', null)
            ->where('notifications.type', 'App\\Notifications\\AdminNotification')
            ->orderBy('notifications.created_at', 'desc')
            ->get();

        $formatted_notification = $notifications->map(function($notification) {
            $notification->data = json_decode($notification->data);
            return $notification;
        });

        if ($notifications){
            return response()->json([
                'message' => 'Notification list is given below',
                'data' => $formatted_notification
            ]);
        }

    }



}
