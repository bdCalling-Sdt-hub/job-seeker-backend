<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use Illuminate\Http\Request;

class CanditedController extends Controller
{
    public function apply_now(Request $request)
    {
        $auth = auth()->user()->id;
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
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Applicaton faile',
            ], 200);
        }
    }
}
