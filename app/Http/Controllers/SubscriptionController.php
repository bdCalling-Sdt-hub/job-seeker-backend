<?php

namespace App\Http\Controllers;

use App\Events\SendNotificationEvent;
use App\Models\JobPost;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    //

    public function aliveSubscription()
    {
        $auth_user_id = auth()->user()->id;
        $have_subscription = Subscription::with('package')->where('user_id',$auth_user_id)->latest()->first();

        // Check if subscription exists
        if($have_subscription == null){
            return response()->json([
                'message' => 'Purchase a subscription to post jobs.',
            ], 403);
        }

        // Check if post limit is reached
        $totalPosts = JobPost::where('user_id', auth()->user()->id)
            ->where('subscription_id', $have_subscription->id)
            ->count();

        if ($have_subscription->package->post_limit <= $totalPosts) {
            return response()->json([
                'message' => 'You have reached the post limit for your subscription.',
            ], 403);
        }

        // Check if subscription is still valid
        $check_package_date = Subscription::where('user_id', auth()->user()->id)
            ->whereDate('end_date', '>', now())
            ->first();

        if (!$check_package_date) {
            return response()->json([
                'message' => 'Your subscription has expired. Please renew to continue posting jobs.',
            ], 403);
        }

        return response()->json([
            'message' => 'You already have an active subscription.',
        ], 200);
    }


    public function recruiterSubscription(Request $request)
    {
        $status = $request->status;
        // if subscription is successful
        if ($status == 'successful') {
            $package = Package::find($request->package_id);

            // Calculate end date based on package duration
            $endDate = Carbon::now()->addMonths($package->duration);
            $auth_user = auth()->user()->id;
            $subscription = new Subscription();
            $subscription->package_id = $package->id;
            $subscription->user_id = $auth_user;
            $subscription->tx_ref = $request->tx_ref;
            $subscription->amount = $request->amount;
            $subscription->currency = $request->currency;
            $subscription->payment_type = $request->payment_type;
            $subscription->status = $request->status;
            $subscription->email = $request->email;
            $subscription->name = $request->name;
            $subscription->end_date = $endDate;
            $subscription->save();
           $admin_result = app('App\Http\Controllers\NotificationController')->sendAdminNotification('Recruiter Purchase a subscription',$subscription->created_at,$subscription->name,$subscription);
            if ($subscription) {
                $user = User::find($auth_user);
                $subscriptions = Subscription::where('user_id',$auth_user)->first();
                if ($user) {
                    $user->user_status = 1;
                    $user->save();
                }
                $admin_result = app('App\Http\Controllers\NotificationController')->sendAdminNotification('Recruiter Purchase a subscription',$subscription->created_at,$subscription->name,$subscription);
                event(new SendNotificationEvent('Recruiter Purchase a subscription',$subscription->created_at,auth()->user()));
                return response()->json([
                    'status' => 'success',
                    'message' => 'subscription complete',
                    'data' => $subscription,
                    'notification' => $admin_result
                ], 200);
            }

        } elseif ($status == 'cancelled') {
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Your subscription is canceled'
            ],499);
        } else {
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Your transaction has been failed'
            ],402);
        }
    }


    public function userSubscription(Request $request){
        $status = $request->status;

        // if subscription is successful
        if ($status == 'successful') {

            $auth_user = auth()->user()->id;
            $user = Subscription::where('user_id', $auth_user)->latest()->first();

            $subscription = new Subscription();
            $subscription->package_id = $request->package_id;
            $subscription->user_id = $request->user_id;
            $subscription->tx_ref = $request->tx_ref;
            $subscription->amount = $request->amount;
            $subscription->currency = $request->currency;
            $subscription->payment_type = $request->payment_type;
            $subscription->status = $request->status;
            $subscription->email = $request->email;
            $subscription->name = $request->name;
            $subscription->save();
            if ($subscription) {
                $user = User::find($auth_user);
                $subscriptions = Subscription::where('user_id',$auth_user)->first();
                if ($user) {
                    $user->user_status = 1;
                    $user->save();
                }
                if ($subscriptions){
                    $newEndDate = Carbon::parse($subscription->end_date)->addMonth();
                    $subscription->end_date = $newEndDate;
                    $subscription->update();
                    $admin_result = app('App\Http\Controllers\NotificationController')->sendAdminNotification('Purchase a subscription',$subscription->created_at,$subscription->name,$subscription);
                    event(new SendNotificationEvent('Purchase a subscription',$subscription->created_at,auth()->user()));
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'subscription complete',
                    'data' => $subscription,
                ], 200);
            }

        } elseif ($status == 'cancelled') {
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Your subscription is canceled'
            ]);
            // Put desired action/code after transaction has been cancelled here
        } else {
            // return getMessage();
            // Put desired action/code after transaction has failed here
            return response()->json([
                'status' => 'cancelled',
                'message' => 'Your transaction has been failed'
            ]);
        }
    }

    public function mySubscription(Request $request){
        $auth_user_id = auth()->user()->id;
        $my_subscription = Subscription::with('package')
            ->where('user_id', $auth_user_id)
            ->orderBy('created_at', 'desc') // Assuming 'created_at' is the column storing subscription creation timestamp
            ->first();


        if ($my_subscription){
            if(is_string($my_subscription->package->feature)) {
                $my_subscription->package->feature = json_decode($my_subscription->package->feature);
            }
        }
        if ($my_subscription){
            return response()->json([
                'message' => 'success',
                'data' => $my_subscription
            ]);
        } else {
            return response()->json([
                'message' => 'success',
                'data' => $my_subscription
            ]);
        }
    }

//    public function manualSubscription(Request $request)
//    {
//        $status = $request->status;
//
//        // if subscription is successful
//        if ($status == 'successful') {
//            $auth_user = auth()->user();
//            $subscription = new Subscription();
//            $subscription->package_id = $request->package_id;
//            $subscription->user_id = $auth_user->id;
//            $subscription->tx_ref = $request->tx_ref;
//            $subscription->amount = $request->amount;
//            $subscription->currency = $request->currency;
//            $subscription->payment_type = $request->payment_type;
//            $subscription->status = $request->status;
//            $subscription->email = $auth_user->email;
//            $subscription->name = $auth_user->fullName;
//            $subscription->save();
//            return response()->json([
//                'message' => 'Manual Subscription is done, Waiting for admin approval',
//                'data' => $subscription
//            ],200);
//        }
//    }

    public function manualSubscription(Request $request)
    {
        $status = $request->status;

        // if subscription is successful
        if ($status == 'successful') {
            $auth_user = auth()->user();
            $subscription = new Subscription();
            $subscription->package_id = $request->package_id;
            $subscription->user_id = $auth_user->id;

            // Generate a unique transaction reference (tx_ref)
            $tx_ref = uniqid('HC') . '_' . Str::random(10);
            $subscription->tx_ref = $tx_ref;

            $subscription->amount = $request->amount;
            $subscription->currency = $request->currency;
            $subscription->payment_type = $request->payment_type;
            $subscription->status = $request->status;
            $subscription->email = $auth_user->email;
            $subscription->name = $auth_user->fullName;
            $subscription->save();
            return response()->json([
                'message' => 'Manual Subscription is done, Waiting for admin approval',
                'data' => $subscription
            ],200);
        }
    }


    public function approveManualSubscription()
    {
        return $hand_cash_subscription = Subscription::with('package')->where('payment_type','Hand Cash')->get();
    }
}
