<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Mail\sendMailNotification;
use App\Models\Apply;
use App\Models\Emplyer_contact;
use App\Models\JobPost;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use DB;
use Mail;

class EmplyDashboardController extends Controller
{
    public function Counting_dashboard()
    {
        $auth = auth()->user()->id;
        $job_post = JobPost::where('user_id', $auth)->first();
        $job_post_id = $job_post->id;
        $totala_apply = Apply::where('job_post_id', $job_post_id)->count();
        $total_job_post = JobPost::where('user_id', $auth)->count();
        $total_subscribe = Subscription::where('user_id', $auth)->count();
        $total_coust = Subscription::where('user_id', $auth)->sum('amount');
        return response()->json([
            'status' => 'success',
            'total_apply' => $totala_apply,
            'total_job_post' => $total_job_post,
            'total_subscribe' => $total_subscribe,
            'total_cust' => $total_coust,
        ], 200);
    }

    public function yearly_avg_coust(Request $request)
    {
        $auth = auth()->user()->id;
        $request->validate([
            'year' => 'required|integer',
        ]);
        $year = $request->input('year');
        $monthlyIncome = Subscription::select(
            DB::raw('(SUM(amount)) as count'),
            DB::raw('MONTHNAME(created_at) as month_name'),
        )
            ->where('user_id', $auth)
            ->whereYear('created_at', $year)
            ->groupBy('month_name')
            ->get()
            ->toArray();
        return response()->json([
            'status' => 'success',
            'monthly_income' => $monthlyIncome,
        ]);
    }
    public function apply_list()
    {
        $auth = auth()->user()->id;
        $job_lists = JobPost::where('user_id', $auth)->get();  // pluck() returns an array of ids

        foreach ($job_lists as $job_list) {
            $applications = Apply::where('job_post_id', $job_list->id)
                ->with('job_post', 'user', 'category')
                ->orderBy('id', 'asc')
                ->paginate(10);
        }
        return response()->json([
            'status' => 'success',
            'data' => $applications
        ]);
    }

    public function job_search(Request $request)
    {
        $title = $request->jobTitle;
        $keyword = $request->keyWord;
        $status = $request->status;

        $jobPosts = JobPost::where('job_title', 'like', "%$title%")
            ->orWhere('key_word', 'like', "%$keyword%")
            ->get();

        // Decode serialized fields
        foreach ($jobPosts as $jobPost) {
            $jobPost->education = json_decode($jobPost->education);
            $jobPost->additional_requirement = json_decode($jobPost->additional_requirement);
            $jobPost->responsibilities = json_decode($jobPost->responsibilities);
            $jobPost->compensation_other_benifits = json_decode($jobPost->compensation_other_benifits);
            $jobPost->key_word = json_decode($jobPost->key_word);
        }

        return response()->json([
            'status' => 'success',
            'data' => $jobPosts
        ]);
    }

    public function job_filter(Request $request)
    {
        $status = $request->status;

        $jobPosts = JobPost::where('status', $status)->get();

        // Decode serialized fields
        foreach ($jobPosts as $jobPost) {
            $jobPost->education = json_decode($jobPost->education);
            $jobPost->additional_requirement = json_decode($jobPost->additional_requirement);
            $jobPost->responsibilities = json_decode($jobPost->responsibilities);
            $jobPost->compensation_other_benifits = json_decode($jobPost->compensation_other_benifits);
            $jobPost->key_word = json_decode($jobPost->key_word);
        }

        return $jobPosts;
    }

    public function applyDetails($applyId)
    {
        $apply_details = Apply::where('id', $applyId)->first();
        $cvId = $apply_details->user_id;
        $jobId = $apply_details->job_post_id;
        $joblist = jobPost::where('id', $jobId)->first();
        $profileInfo = User::where('id', $cvId)->with('candidate', 'education', 'experience', 'training', 'interest')->get();
        $formatted_profileInfo = $profileInfo->map(function ($profile) {
            return $profile;
        });
        return response()->json([
            'message' => 'success',
            'apply_details' => $apply_details,
            'Cv' => $formatted_profileInfo,
            'joblist' => $joblist
        ]);
    }

    public function applyStatus(Request $request)
    {
        $apply_status = Apply::find($request->id);
        if ($apply_status) {
            $apply_status->status = $request->status;
            $apply_status->save();
            if ($apply_status) {
                return response()->json([
                    'statu' => 'success',
                    'message' => ' status update successfully',
                ], 200);
            } else {
                return response()->json([
                    'statu' => 'false',
                    'message' => ' status update fail',
                ], 500);
            }
        } else {
            return response()->json([
                'message' => []
            ]);
        }
    }

    public function select_candited_send_mail(Request $request)
    {
        $email = $request->email;
        $jobName = $request->jobTitle;
        $address = $request->address;
        $date = $request->date;
        $time = $request->time;
        $description = $request->message;
        Mail::to($email)->send(new sendMailNotification($jobName, $address, $date, $time, $description));
    }

    public function send_mail_data(Request $request)
    {
        $email = $request->email;
        $subject = $request->subject;
        $description = $request->description;
        Mail::to($email)->send(new SendMail($subject, $description));
    }

    public function subscription()
    {
        $auth = auth()->user()->id;
        $subscribe = Subscription::where('user_id', $auth)->with('User', 'package')->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $subscribe
        ]);
    }

//    public function subscription_details($id)
//    {
//        $subscribe = Subscription::where('id', $id)->with('User', 'package')->first();
//        $subscribe_job = Subscription::where('id', $id)->first();
//        $package_id = $subscribe_job->package_id;
//        $job_list = JobPost::where('package_id', $package_id)->paginate(10);
//
//        return response()->json([
//            'status' => 'success',
//            'subscribe' => $subscribe,
//            'job_list' => $job_list
//        ]);
//    }

    public function subscription_details($id)
    {
        $subscribe = Subscription::where('id', $id)->with('User.recruiter', 'package')->first();
        $subscribe_job = Subscription::where('id', $id)->first();
        $package_id = $subscribe_job->package_id;
        $job_list = JobPost::where('package_id', $package_id)
            ->with('recruiter', 'category')
            ->paginate(10);

        $package = $subscribe->package;
        $package->feature = json_decode($package->feature);

        return response()->json([
            'status' => 'success',
            'subscribe' => $subscribe,
            'job_list' => $job_list
        ]);
    }

    // ====================CONTACT=====================//

    public function post_contact(Request $request)
    {
        $reciver = User::where('userType', 'ADMIN')->first();
        $reciver_id = $reciver->id;
        $new_contact = new Emplyer_contact();
        $new_contact->sender_id = auth()->user()->id;
        $new_contact->reciver_id = $reciver_id;
        // $new_contact->subject = $request->subject;
        $new_contact->body = $request->message;
        $new_contact->save();
        if ($new_contact) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data insert successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Data insert faile'
            ], 400);
        }
    }

    public function message_inbox()
    {
        $auth = auth()->user()->id;
        $message = Emplyer_contact::where('reciver_id', $auth)->paginate(10);

        if ($message) {
            return response()->json([
                'status' => 'success',
                'data' => $message
            ], 200);
        }
    }

    public function send_message()
    {
        $auth = auth()->user()->id;
        $send_message = Emplyer_contact::where('sender_id', $auth)->paginate(10);

        if ($send_message) {
            return response()->json([
                'status' => 'success',
                'message' => $send_message
            ]);
        }
    }
}
