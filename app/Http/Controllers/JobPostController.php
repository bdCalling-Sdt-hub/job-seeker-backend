<?php

namespace App\Http\Controllers;

use App\Models\Apply;
use App\Models\JobPost;
use App\Models\Recruiter;
use App\Models\Subscription;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function create_job(Request $request)
    {
        $auth = auth()->user()->id;
        $packageId = $request->packageId;
        $date = date('Y-m-d H:i:s');
        $check_subscribe = Subscription::where('user_id', $auth)->count();
        $check_packageId = Subscription::where('user_id', $auth)->where('package_id', $packageId)->count();
        $check_package_date = Subscription::where('user_id', $auth)
            ->where('package_id', $packageId)
            ->whereDate('end_date', '<', now())
            ->count();

        if (!$check_subscribe) {
            return response()->json([
                'status' => 'error',
                'message' => 'You have no subscription'
            ]);
        } elseif (!$check_packageId) {
            return response()->json([
                'status' => 'error',
                'message' => 'Package not avalable'
            ]);
        } elseif (!$check_package_date) {
            $recruiter = Recruiter::where('user_id', $auth)->first();
            $recruiter_id = $recruiter->id;
            $create_job = new JobPost();
            $create_job->package_id = $request->packageId;
            $create_job->subscription_id = $request->subscribId;
            $create_job->recruiter_id = $recruiter_id;
            $create_job->user_id = $auth;
            $create_job->job_title = $request->job_title;
            $create_job->application_last_date = $request->dadLine;
            $create_job->salary = $request->salary;
            $create_job->job_type = $request->job_type;
            $create_job->work_type = $request->work_type;
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
                    'message' => 'faile your job post',
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

    public function edit_job($id)
    {
        $edit_job = JobPost::where('id', $id)->first();
        if ($edit_job) {
            return response()->json([
                'status' => 'success',
                'data' => $edit_job
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'faile your job post',
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

    // public function show_job(Request $request)
    // {
    //     $title = $request->jobTitle;
    //     $keyword = $request->keyWord;
    //     $status = $request->status;

    //     return $auth = auth()->user()->id;

    //     $display_job = JobPost::where('user_id', $auth)
    //         ->with('Recruiter')
    //         ->orWhere('job_title', 'like', "%$title%")
    //         ->orWhere('key_word', 'like', "%$keyword%")
    //         ->orWhere('status', 'like', "%$status%")
    //         ->orderBy('id', 'desc')
    //         ->paginate(10);

    //     if ($display_job->isNotEmpty()) {
    //         $decode_data = $display_job->toArray();
    //         foreach ($decode_data['data'] as &$job) {
    //             $job['education'] = json_decode($job['education'], true);
    //             $job['additional_requirement'] = json_decode($job['additional_requirement'], true);
    //             $job['responsibilities'] = json_decode($job['responsibilities'], true);
    //             $job['compensation_other_benifits'] = json_decode($job['compensation_other_benifits'], true);
    //             $job['key_word'] = json_decode($job['key_word'], true);
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'data' => $decode_data
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 'false',
    //             'data' => []
    //         ]);
    //     }
    // }

    public function show_job(Request $request)
    {
        // Get authenticated user's ID
        $authUserId = auth()->user()->id;

        // Retrieve request parameters
        $title = $request->jobTitle;
        $keyword = $request->keyWord;
        $status = $request->status;

        // Query to fetch job posts
        $display_job = JobPost::where('user_id', $authUserId)
            ->with('Recruiter')
            ->where(function ($query) use ($title, $keyword, $status) {
                $query
                    ->where('job_title', 'like', "%$title%")
                    ->orWhere('key_word', 'like', "%$keyword%")
                    ->orWhere('status', 'like', "%$status%");
            })
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
            ->with('Recruiter')
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
                'category_id' => $job->category_id,
                'area' => $job->area,
                'education' => $job->education,
                'experience' => $job->experience,
                'additional_requirement' => $job->additional_requirement,
                'responsibilities' => $job->responsibilities,
                'compensation_other_benefits' => $job->compensation_other_benefits,
                'vacancy' => $job->vacancy,
                'status' => $job->status,
                'key_word' => $job->key_word,
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
