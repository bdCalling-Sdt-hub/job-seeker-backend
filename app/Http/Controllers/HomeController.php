<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\Recruiter;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function jobFilter(Request $request)
    {
        $job_post = JobPost::get();
        return response()->json([
            'message' => 'Job List',
            'data' => $job_post,
        ]);
    }
}
