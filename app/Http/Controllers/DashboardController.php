<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Recruiter;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function dashboard(){
        $total_employers = User::where('userType','RECRUITER')->where('verify_email','1')->count();
        $total_job_post = JobPost::count();
        $subscriptions = Subscription::all();
        $total_amount = 0;
        foreach ($subscriptions as $subscription) {
            $total_amount += $subscription->amount;
        }
        return response()->json([
            'total_employers' => $total_employers,
            'total_job_post' => $total_job_post,
            'total_earning' => $total_amount,
        ]);
    }

//    public function employerList(Request $request)
//    {
//
//        $employer_list = Recruiter::with('user','category')->get();
//        return response()->json([
//            'message' => 'Employer List',
//            'data' => $employer_list
//        ]);
//
//    }

    public function employerList(Request $request)
    {
        $status = $request->input('status');
        $categoryName = $request->input('category_name');

        $query = Recruiter::with('user', 'category');

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryName) {
            $query->whereHas('category', function ($query) use ($categoryName) {
                $query->where('category_name', $categoryName);
            });
        }

        $employerList = $query->get();

        return response()->json([
            'message' => 'Employer List',
            'data' => $employerList
        ]);
    }

    public function companyWiseSubscription(Request $request)
    {
        //recruiter id / company id
        $user_id = $request->user_id;
        $recruiter_wise_subscription_list = [];
        $recruiter_wise_subscription = Recruiter::with('user','category')->where('user_id',$user_id)->first();
        $subscription = Subscription::with('package')->where('user_id',$user_id)->get();
        $recruiter_wise_subscription_list = [
            'company_details' => $recruiter_wise_subscription,
            'subscription' => $subscription
        ];
        return response()->json([
            'message'=> 'recruiter wise subscription list',
            'data' => $recruiter_wise_subscription_list
        ]);

    }



}
