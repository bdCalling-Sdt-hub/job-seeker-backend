<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use App\Models\JobPost;
use App\Models\Recruiter;
use App\Models\Subscription;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function show_subscribe_package()
    {
        $auth = auth()->user()->id;
        return $show_subscribe = Subscription::where('user_id', $auth)->with('package')->get();
    }

//    public function create_job(Request $request)
//    {
//        $auth = auth()->user()->id;
//        $packageId = $request->packageId;
//        $date = date('Y-m-d H:i:s');
//        $check_subscribe = Subscription::where('user_id', $auth)->count();
//        $check_packageId = Subscription::where('user_id', $auth)->where('package_id', $packageId)->count();
//        $check_package_date = Subscription::where('user_id', $auth)
//            ->where('package_id', $packageId)
//            ->whereDate('end_date', '<', now())
//            ->count();
//
//        if (!$check_subscribe) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'You have no subscription'
//            ]);
//        } elseif (!$check_packageId) {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Package not available'
//            ]);
//        } elseif (!$check_package_date) {
//            $recruiter = Recruiter::where('user_id', $auth)->first();
//            $recruiter_id = $recruiter->id;
//            $create_job = new JobPost();
//            $create_job->package_id = $request->packageId;
//            $create_job->subscription_id = $request->subscribId;
//            $create_job->recruiter_id = $recruiter_id;
//            $create_job->user_id = $auth;
//            $create_job->job_title = $request->job_title;
//            $create_job->application_last_date = $request->dadLine;
//            $create_job->salary = $request->salary;
//            $create_job->job_type = $request->job_type;
//            $create_job->work_type = $request->work_type;
//            $create_job->work_shift = $request->work_shift;
//            $create_job->category_id = $request->category_id;
//            $create_job->area = $request->area;
//            $create_job->education = $request->education;
//            $create_job->experience = $request->experience;
//            $create_job->additional_requirement = $request->additional_requirement;
//            $create_job->responsibilities = $request->responsibilities;
//            $create_job->compensation_other_benifits = $request->other_benifits;
//            $create_job->vacancy = $request->vacancy;
//            $create_job->key_word = $request->key_word;
//            $create_job->status = 'pending';
//            $create_job->save();
//            if ($create_job) {
//                return response()->json([
//                    'status' => 'success',
//                    'message' => 'success your job post',
//                    'data' => $create_job
//                ], 200);
//            } else {
//                return response()->json([
//                    'status' => 'false',
//                    'message' => 'failed your job post',
//                    'data' => []
//                ], 500);
//            }
//        } else {
//            return response()->json([
//                'status' => 'error',
//                'message' => 'Package time over'
//            ]);
//        }
//    }

    public function create_job(Request $request)
    {
        $auth = auth()->user()->id;
        $date = date('Y-m-d H:i:s');
        $check_package_date = Subscription::with('package')->where('user_id', $auth)
            ->whereDate('end_date', '>', now())
            ->first();

        if (!empty($check_package_date)) {
            $recruiter = Recruiter::where('user_id', $auth)->first();
            $recruiter_id = $recruiter->id;
            $create_job = new JobPost();
            $create_job->package_id = $check_package_date->package->id;
            $create_job->subscription_id = $check_package_date->id;
            $create_job->recruiter_id = $recruiter_id;
            $create_job->user_id = $auth;
            $create_job->job_title = $request->job_title;
            $create_job->application_last_date = $request->dadLine;
            $create_job->salary = $request->salary;
            $create_job->job_type = $request->job_type;
            $create_job->work_type = $request->work_type;
            $create_job->work_shift = $request->work_shift;
            $create_job->category_id = $request->category_id;
            $create_job->area = $request->area;
            $create_job->education = $request->education;
            $create_job->experience = $request->experience;
            $create_job->additional_requirement = $request->additional_requirement;
            $create_job->responsibilities = $request->responsibilities;
            $create_job->compensation_other_benifits = $request->other_benifits;
            $create_job->vacancy = $request->vacancy;
            $create_job->key_word = $request->key_word;
            $create_job->status = 'pending';
            $create_job->save();
            if ($create_job) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'success your job post',
                    'data' => $create_job
                ], 200);
            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => 'failed your job post',
                    'data' => []
                ], 500);
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Package time over'
            ]);
        }
    }


    public function createJob(Request $request)
    {
//        $subscription = $this->checkSubscription();
//        if(empty($subscription))
//        {
//            return response()->json(['message' => 'You do not have subscription'],402);
//        }
//        $auth_user_id = auth()->user()->id;
//
//        if ($subscription->package->post_limit_exceeded()) {
//            return response()->json(['message' => 'Post limit exceeded for this package.'], 400);
//        }
//
//        if ($subscription->package->hasExpired()) {
//            return response()->json(['message' => 'Subscription package has expired.'], 400);
//        }
//        $recruiter = Recruiter::where('user_id', $auth_user_id)->first();
        $auth_user_id = auth()->user()->id;
        $have_subscription = Subscription::with('package')->latest()->first();

        // Check if subscription exists
        if(empty($have_subscription)){
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
        if (empty($recruiter))
        {
            return response()->json([
                'message' => "Please update your profile; some company information is missing.",
            ],412);
        }
        $recruiter_id = $recruiter->id;
        $create_job = new JobPost();
        $create_job->package_id = $have_subscription->package->id;
        $create_job->subscription_id = $have_subscription->id;
        $create_job->recruiter_id = $recruiter_id;
        $create_job->user_id = $auth_user_id;
        $create_job->job_title = $request->job_title;
        $create_job->application_last_date = $request->dadLine;
        $create_job->salary = $request->salary;
        $create_job->job_type = $request->job_type;
        $create_job->work_type = $request->work_type;
        $create_job->work_shift = $request->work_shift;
        $create_job->category_id = $request->category_id;
        $create_job->area = $request->area;
        $create_job->education = $request->education;
        $create_job->experience = $request->experience;
        $create_job->additional_requirement = $request->additional_requirement;
        $create_job->responsibilities = $request->responsibilities;
        $create_job->compensation_other_benifits = $request->other_benifits;
        $create_job->vacancy = $request->vacancy;
        $create_job->key_word = $request->key_word;
        $create_job->status = 'pending';
        $create_job->save();
        return response()->json([
            'message' => 'Job Created Successfully',
            'data' => 'job post',
        ],200);
    }

    private function checkSubscription()
    {
        $auth_user_id = auth()->user()->id;
        return Subscription::with('package')->where('user_id', $auth_user_id)->first();
    }








    public function edit_job($id)
    {
        $edit_job = JobPost::where('id', $id)->with('user', 'category', 'recruiter')->first();

        if ($edit_job) {
            $edit_job->education = json_decode($edit_job->education, true);
            $edit_job->additional_requirement = json_decode($edit_job->additional_requirement, true);
            $edit_job->responsibilities = json_decode($edit_job->responsibilities, true);
            $edit_job->compensation_other_benifits = json_decode($edit_job->compensation_other_benifits, true);
            $edit_job->key_word = json_decode($edit_job->key_word, true);
            $edit_job->recruiter->company_service = json_decode($edit_job->recruiter->company_service, true);

            return response()->json([
                'status' => 'success',
                'data' => $edit_job
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Failed to retrieve the job post',
                'data' => []
            ], 200);
        }
    }

    public function update_job(Request $request)
    {
        $auth = auth()->user()->id;
        $recruiter = Recruiter::where('user_id', $auth)->first();
        $recruiter_id = $recruiter->id;
        $update_job = JobPost::find($request->id);
        $update_job->id = $request->id;
        $update_job->recruiter_id = $recruiter_id;
        $update_job->package_id = $recruiter->packageId;
        $update_job->subscription_id = $request->subscribId;
        $update_job->job_title = $request->job_title;
        $update_job->work_type = $request->work_type;
        $update_job->work_shift = $request->work_shift;
        $update_job->application_last_date = $request->dadLine;
        $update_job->salary = $request->salary;
        $update_job->job_type = $request->job_type;
        $update_job->category_id = $request->category_id;
        $update_job->area = $request->area;
        $update_job->education = $request->education;
        $update_job->experience = $request->experience;
        $update_job->additional_requirement = $request->additional_requirement;
        $update_job->responsibilities = $request->responsibilities;
        $update_job->compensation_other_benifits = $request->other_benifits;
        $update_job->vacancy = $request->vacancy;
        $update_job->key_word = $request->key_word;
        $update_job->save();
        if ($update_job) {
            return response()->json([
                'status' => 'success',
                'message' => 'success update your job',
                'data' => $update_job
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'faile update your job post',
                'data' => []
            ], 500);
        }
    }

    public function delete_job($id)
    {
        $delete_job = JobPost::where('id', $id)->delete();
        if ($delete_job) {
            return response()->json([
                'status' => 'success',
                'message' => 'successfully delete'
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'faile your job delete'
            ]);
        }
    }

    public function show_job(Request $request)
    {
        // Get authenticated user's ID
        $authUserId = auth()->user()->id;

        // Retrieve request parameters
        // $title = $request->jobTitle;
        // $keyword = $request->keyWord;
        // $status = $request->status;

        // Query to fetch job posts
        $display_job = JobPost::where('user_id', $authUserId)
            ->with('Recruiter', 'user', 'category')
            // ->where(function ($query) use ($title, $keyword, $status) {
            //     $query
            //         ->where('job_title', 'like', "%$title%")
            //         ->orWhere('key_word', 'like', "%$keyword%")
            //         ->orWhere('status', 'like', "%$status%");
            // })
            ->orderBy('id', 'desc')
            ->paginate(10);

        // Process and return the result
        if ($display_job->isNotEmpty()) {
            $decode_data = $display_job->toArray();
            foreach ($decode_data['data'] as &$job) {
                $job['education'] = json_decode($job['education'], true);
                $job['additional_requirement'] = json_decode($job['additional_requirement'], true);
                $job['responsibilities'] = json_decode($job['responsibilities'], true);
                $job['compensation_other_benifits'] = json_decode($job['compensation_other_benifits'], true);
                $job['key_word'] = json_decode($job['key_word'], true);
            }

            return response()->json([
                'status' => 'success',
                'data' => $decode_data
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'data' => []
            ]);
        }
    }

    // public function apply_job_show()
    // {
    //     $auth = auth()->user()->id;
    //     $recruiter = Recruiter::where('user_id', $auth)->with('user')->first();

    //     if (!$recruiter) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Recruiter not found for the logged-in user.'
    //         ]);
    //     }

    //     $recruiter_id = $recruiter->id;
    //     $display_job = JobPost::where('recruiter_id', $recruiter_id)
    //         ->where('status', 'published')
    //         ->with('Recruiter')
    //         ->orderBy('id', 'desc')
    //         ->paginate(10);
    //     $jobsWithApplicationsCount = [];

    //     foreach ($display_job as $job) {
    //         $apply_count = Apply::where('job_post_id', $job->id)->count();
    //         $jobsWithApplicationsCount[] = [
    //             'applied_count' => $apply_count,
    //             'id' => $job->id,
    //             'recruiter_id' => $job->recruiter_id,
    //             'job_title' => $job->job_title,
    //             'application_last_date' => $job->application_last_date,
    //             'salary' => $job->salary,
    //             'job_type' => $job->job_type,
    //             'work_type' => $job->work_type,
    //             'category_id' => $job->category_id,
    //             'area' => $job->area,
    //             'education' => $job->education,
    //             'experience' => $job->experience,
    //             'additional_requirement' => $job->additional_requirement,
    //             'responsibilities' => $job->responsibilities,
    //             'compensation_other_benefits' => $job->compensation_other_benefits,
    //             'vacancy' => $job->vacancy,
    //             'status' => $job->status,
    //             'key_word' => $job->key_word,
    //             'created_at' => $job->created_at,
    //             'updated_at' => $job->updated_at,
    //             'recruiter' => $job->Recruiter,
    //         ];
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $jobsWithApplicationsCount,
    //         'pagination' => [
    //             'current_page' => $display_job->currentPage(),
    //             'per_page' => $display_job->perPage(),
    //             'total' => $display_job->total(),
    //             'last_page' => $display_job->lastPage(),
    //         ]
    //     ]);
    // }

    public function apply_job_show()
    {

        $auth = auth()->user()->id;
        $recruiter = Recruiter::where('user_id', $auth)->first();

        if (!$recruiter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recruiter not found for the logged-in user.'
            ]);
        }

        $recruiter_id = $recruiter->id;
        $display_job = JobPost::where('recruiter_id', $recruiter_id)
            ->where('status', 'published')
            ->with(['Recruiter', 'Recruiter.user'])
            ->orderBy('id', 'desc')
            ->paginate(10);
        $jobsWithApplicationsCount = [];

        foreach ($display_job as $job) {
            $apply_count = Apply::where('job_post_id', $job->id)->count();
            $jobsWithApplicationsCount[] = [
                'applied_count' => $apply_count,
                'id' => $job->id,
                'recruiter_id' => $job->recruiter_id,
                'job_title' => $job->job_title,
                'application_last_date' => $job->application_last_date,
                'salary' => $job->salary,
                'job_type' => $job->job_type,
                'work_type' => $job->work_type,
                'work_shift' => $job->work_shift,
                'category_id' => $job->category_id,
                'area' => $job->area,
                'education' => $job->education,
                'experience' => $job->experience,
                'additional_requirement' => $job->additional_requirement,
                'responsibilities' => $job->responsibilities,
                'compensation_other_benifits' => $job->compensation_other_benifits,
                'vacancy' => $job->vacancy,
                'status' => $job->status,
                'key_word' => json_decode($job->key_word),
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
                'recruiter' => $job->Recruiter,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $jobsWithApplicationsCount,
            'pagination' => [
                'current_page' => $display_job->currentPage(),
                'per_page' => $display_job->perPage(),
                'total' => $display_job->total(),
                'last_page' => $display_job->lastPage(),
            ]
        ]);
    }
}
