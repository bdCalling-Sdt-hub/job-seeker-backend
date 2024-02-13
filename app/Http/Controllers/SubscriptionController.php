<?php

namespace App\Http\Controllers;

use App\Models\subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    //

    public function userSubscription(Request $request){
        $status = $request->status;

        // if subscription is successful
        if ($status == 'successful') {

            $auth_user = $request->user_id;
            $user = Subscription::where('user_id', $auth_user)->first();

            if (!$user) {
                $subscription = new Subscription();
            } else {
                $subscription = $user;
            }
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
                    $newEndDate = Carbon::parse($subscription->created_at)->addMonth();
                    $subscription->end_date = $newEndDate;
                    $subscription->update();
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
        $my_subscription = Subscription::with('package')->where('user_id',$auth_user_id)->first();

        if ($my_subscription){
            return response()->json([
                'message' => 'success',
                'data' => $my_subscription
            ]);
        }else {
            return response()->json([
                'message' => 'success',
                'data' => []
            ]);
        }

    }


}
