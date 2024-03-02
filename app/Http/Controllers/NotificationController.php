<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Notifications\AdminNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

    public function notification(){

        $user = $this->guard()->user();

            if ($user) {
                $notifications = $user->notifications()->select('id', 'type', 'data', 'read_at', 'created_at')
                    ->orderBy('created_at', 'desc')->get();

                return response()->json([
                    'message' => 'notification list',
                    'notifications' => $notifications,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not  found',
                ], 401);
            }
    }

    public function markRead()
    {
        $user = $this->guard()->user();
        $notifications = [];
        if ($user) {
            $notifications = $user->unreadNotifications()->orderBy('created_at', 'desc')->get();
            // Mark all unread notifications as read
            $user->unreadNotifications->markAsRead();

            // Retrieve and return the updated notifications
            $updated_notifications = $user->notifications;
            $notifications = [
                'unread_notification' => $notifications,
                'read_notification' => $updated_notifications,
            ];
            return response()->json([
                'message' => 'Notifications',
                'notification' => $notifications,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated',
            ], 401);
        }
    }

    public function adminNotification(){

        $Notifications = DB::table('notifications')
            ->where('notifications.type', 'App\\Notifications\\AdminNotification')
            ->orderBy('notifications.created_at', 'desc')
            ->paginate(9);


        $formattedReadNotifications = $Notifications->map(function($notification) {
            $notification->data = json_decode($notification->data);
            return $notification;
        });

        return response()->json([
            'message' => 'Notifications are given below',
            'Notifications' => $formattedReadNotifications,

            'pagination' => [
                'current_page' => $Notifications->currentPage(),
                'total_pages' => $Notifications->lastPage(),
                'per_page' => $Notifications->perPage(),
                'total' => $Notifications->total(),
                'next_page_url' => $Notifications->nextPageUrl(),
                'prev_page_url' => $Notifications->previousPageUrl(),
            ]
        ]);
    }

    public function readNotificationById(Request $request)
    {
        $notification = DB::table('notifications')->find($request->id);
        if ($notification) {
            $notification->read_at = Carbon::now();
            DB::table('notifications')->where('id', $notification->id)->update(['read_at' => $notification->read_at]);
            return response()->json([
                'status' => 'success',
                'message' => 'Notification read successfully.',
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Notification not found',
            ], 404);
        }
    }

}
