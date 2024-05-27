<?php

namespace App\Http\Controllers;

use App\Mail\ApplicantApplyEmail;
use App\Mail\ApplyApplicationMail;
use App\Mail\SendMail;
use App\Mail\sendMailNotification;
use App\Models\Apply;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\RecruiterNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CanditedController extends Controller
{
    public function apply_now(Request $request)
    {
        $auth = auth()->user()->id;
        $jobId = $request->jobPostId;
        $recuriter = JobPost::with('user')->where('id', $jobId)->first();
        $recuriterId = $recuriter->user_id;
        $recruiterUser = User::find($recuriterId);
        $user = auth()->user();
        $check = Apply::where('user_id', $auth)->where('job_post_id', $jobId)->count();
        if ($check) {
            return response()->json([
                'message' => 'you already applied for this position'
            ],409);
        } else {
            $application = new Apply();

            $application->user_id = $auth;
            $application->job_post_id = $request->jobPostId;
            $application->category_id = $request->catId;
            $application->interest = $request->interest;
            $application->experience = $request->experience;
            $application->salary = $request->salary;
            $application->cv = $request->cv;
            $application->save();
            $subject = 'Job Application Successfull';
            $message = 'New Applicant Applied for the job post';
            $description = 'You have been successfully applied for the job post. This is demo text, later on we will change according to your concern';
            $user_email = auth()->user()->email;
            $recruiter_email = $recuriter->user->email;
            Mail::to($user_email)->send(new ApplyApplicationMail($subject,$description));
            if ($recruiter_email){
                Mail::to($recruiter_email)->send(new ApplicantApplyEmail($message));
            }
            $result = app('App\Http\Controllers\NotificationController')->sendRecruiterNotification('Candidate Applied for The Job', $user->created_at, $user->fullName, $recruiterUser);
            if ($application) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'You applied Successfully',
                    'notification' => $result,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Something went wrong',
                ], 400);
            };
        }
    }
}
