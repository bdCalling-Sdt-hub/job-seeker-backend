<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use App\Models\JobPost;
use App\Models\User;
use App\Notifications\RecruiterNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CanditedController extends Controller
{
    public function apply_now(Request $request)
    {
        $auth = auth()->user()->id;
        $jobId = $request->jobPostId;
        $recuriter = JobPost::where('id', $jobId)->first();
        $recuriterId = $recuriter->user_id;
        $recruiterUser = User::find($recuriterId);
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
            $result = app('App\Http\Controllers\NotificationController')->sendRecruiterNotification('Candidate Applied for The Job', $recruiterUser->created_at, $recruiterUser->fullName, $recruiterUser);
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
