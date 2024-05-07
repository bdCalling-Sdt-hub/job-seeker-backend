<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use App\Models\CompanyBookmark;
use App\Models\JobPost;
use App\Models\User;
use Illuminate\Http\Request;

class BookMarkController extends Controller
{
    //
    public function toggleBookmark(Request $request)
    {
        $user_id = auth()->user()->id;
        $job_post_id = $request->job_post_id;

        $bookmark = Bookmark::where('user_id', $user_id)->where('job_post_id', $job_post_id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'message' => 'Bookmark deleted'
            ]);
        } else {
            $bookmark_info = new BookMark();
            $bookmark_info->user_id = $user_id;
            $bookmark_info->job_post_id = $request->job_post_id;
            $bookmark_info->status = true;
            $bookmark_info->save();
            return response()->json([
                'message' => 'Bookmark added',
                'data' => $bookmark_info,
            ]);
        }
    }


    public function bookmarksData()
    {
        $user_id = auth()->user()->id;
        $bookmarks = Bookmark::with('user', 'job_post.recruiter')->where('user_id', $user_id)->paginate(9);
        return response()->json([
            'message' => 'Bookmarked job list',
            'data' => $bookmarks,
        ]);

    }

    public function companyToggleBookmark(Request $request)
    {
        $user_id = auth()->user()->id;
        $recruiter_id = $request->recruiter_id;

        $company_bookmark = CompanyBookmark::where('user_id',$user_id)->where('recruiter_id',$recruiter_id)->first();

        if ($company_bookmark) {
            $company_bookmark->delete();
            return response()->json([
                'message' => 'Company Bookmark deleted'
            ]);
        } else {
            $company_bookmark_info = new CompanyBookmark();
            $company_bookmark_info->user_id = $user_id;
            $company_bookmark_info->recruiter_id = $request->recruiter_id;
            $company_bookmark_info->status = true;
            $company_bookmark_info->save();
            return response()->json([
                'message' => 'Company Bookmark added',
                'data' => $company_bookmark_info,
            ]);
        }
    }

    public function companyBookmarksData()
    {
//        $user_id = auth()->user()->id;
//        $bookmarks = CompanyBookmark::with('user', 'job_post.recruiter')->where('user_id', $user_id)->paginate(9);
//        return response()->json([
//            'message' => 'Bookmarked job list',
//            'data' => $bookmarks,
//        ]);

        $user_id = auth()->user()->id;
        $recruiters = CompanyBookmark::where('user_id', $user_id)->paginate(9);

        $recruiterWiseJobPosts = [];
        foreach ($recruiters as $recruiter) {
            // Fetch job posts related to the current category
            $job_posts = JobPost::with('recruiter', 'user', 'category')
                ->where('recruiter_id', $recruiter->recruiter_id)->get();

            // Count the number of job posts for the current category
            $jobPostCount = $job_posts->count();

            if ($jobPostCount != 0) {
                $formatted_job_list = $job_posts->map(function ($job) {
                    $job->education = json_decode($job->education);
                    $job->additional_requirement = json_decode($job->additional_requirement);
                    $job->compensation_other_benifits = json_decode($job->compensation_other_benifits);
                    $job->key_word = json_decode($job->key_word);
                    $job->responsibilities = json_decode($job->responsibilities);
                    if (is_string($job->recruiter->company_service)) {
                        $job->recruiter->company_service = json_decode($job->recruiter->company_service);
                    }
//                $job->recruiter->company_service = json_decode($job->recruiter->company_service);
                    return $job;
                });

                $recruiterWiseJobPosts[] = [
                    'job_posts' => $formatted_job_list,
                    'job_post_count' => $jobPostCount
                ];
            }
            // Check if each job post is bookmarked by the user
            $bookmarked_job_ids = Bookmark::where('user_id', auth()->user()->id)
                ->pluck('job_post_id')
                ->toArray();

            foreach ($job_posts as $job_post) {
                if (in_array($job_post->id, $bookmarked_job_ids)) {
                    $job_post->is_bookmarked = true;
                } else {
                    $job_post->is_bookmarked = false;
                }
            }
        }
        return response()->json([
            'message' => 'Company Wise Bookmark Job Post',
            'data' => $recruiterWiseJobPosts
        ]);
    }

}
