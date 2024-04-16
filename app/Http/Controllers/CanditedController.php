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
                'message' => 'all ready applyed'
            ]);
        } else {
            $application = new Apply();

            $application->user_id = $auth;
            $application->job_post_id = $request->jobPostId;
            $application->category_id = $request->catId;
            $application->interest = $request->interest;
            $application->experience = $request->experience;
            $application->salary = $request->salary;
            if ($request->hasfile('cv')) {
                $file = $request->file('cv');
                $extenstion = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extenstion;
                $file->move('images/', $filename);
                $application->cv = $filename;
            }
            $application->save();
            if ($application) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Applicaton successfully',
                    'notification' => Notification::send($recruiterUser, new RecruiterNotification($application))
                ], 200);
            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Applicaton faile',
                ], 200);
            };
        }
    }
}
