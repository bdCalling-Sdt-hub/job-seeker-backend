<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use App\Models\JobPost;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

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
        $application_list = Apply::where('user_id', $auth)->with('job_post', 'User', 'Category')->orderBy('id', 'asc')->paginate(10);
        return response()->json([
            'status=' => 'success',
            'data' => $application_list
        ]);
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

    public function select_candited_send_mail()
    {
        return 'mail sendig';
    }
}
