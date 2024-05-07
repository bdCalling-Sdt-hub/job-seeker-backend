<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use App\Models\Category;
use App\Models\CompanyBookmark;
use App\Models\JobPost;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function jobFilter(Request $request)
    {
        $user_id = auth()->user()->id; // Get authenticated user id

        $query = JobPost::query();

        if ($request->filled('job_title')) {
            $query->where('job_title', 'like', '%' . $request->input('job_title') . '%');
        }

        if ($request->filled('key_word')) {
            $query->where('key_word','like', '%' . $request->input('key_word'));
        }

        if ($request->filled('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->filled('work_type')) {
            $query->where('work_type', $request->input('work_type'));
        }

        if ($request->filled('work_category')) {
            $query->where('category_id', $request->input('work_category'));
        }

        if ($request->filled('experience')) {
            $query->where('experience', '<=', $request->input('experience'));
        }

        if ($request->filled('area')) {
            $query->where('area', $request->input('area'));
        }
        if ($request->filled('salary')) {
            $query->where('salary', $request->input('salary'));
        }

        $job_posts = $query->with('user', 'recruiter', 'category')
            ->where('status', 'published')
            ->paginate(9);

        $formatted_job_list = $job_posts->map(function ($job) {
            $job->education = json_decode($job->education);
            $job->additional_requirement = json_decode($job->additional_requirement);
            $job->compensation_other_benifits = json_decode($job->compensation_other_benifits);
            $job->key_word = json_decode($job->key_word);
            $job->responsibilities = json_decode($job->responsibilities);
            if (is_string($job->recruiter->company_service)) {
                $job->recruiter->company_service = json_decode($job->recruiter->company_service);
            }
//            $job->recruiter->company_service = json_decode($job->recruiter->company_service);
            return $job;
        });

        // Check if each job post is bookmarked by the user
        $bookmarked_job_ids = Bookmark::where('user_id', $user_id)
            ->pluck('job_post_id')
            ->toArray();

        foreach ($job_posts as $job_post) {
            if (in_array($job_post->id, $bookmarked_job_ids)) {
                $job_post->is_bookmarked = true;
            } else {
                $job_post->is_bookmarked = false;
            }
        }
        return response()->json([
            'message' => 'Filtered Job List',
            'current_page' => $job_posts->currentPage(),
            'data' => $formatted_job_list,
            'first_page_url' => $job_posts->url(1),
            'from' => $job_posts->firstItem(),
            'last_page' => $job_posts->lastPage(),
            'last_page_url' => $job_posts->url($job_posts->lastPage()),
            'links' => $job_posts->links(),
            'next_page_url' => $job_posts->nextPageUrl(),
            'path' => $job_posts->url($job_posts->currentPage()),
            'per_page' => $job_posts->perPage(),
            'prev_page_url' => $job_posts->previousPageUrl(),
            'to' => $job_posts->lastItem(),
            'total' => $job_posts->total(),
        ]);
    }

    public function showCategoryandCount()
    {
        $categories = Category::all();
        // Initialize an empty array to store category-wise job posts
        $categoryWiseJobPosts = [];

        // Loop through each category
        foreach ($categories as $category) {
            // Fetch job posts related to the current category
            $jobPosts = JobPost::where('category_id', $category->id)->get();

            // Count the number of job posts for the current category
            $jobPostCount = $jobPosts->count();

            // Add category name and job posts to the result array
            $categoryWiseJobPosts[] = [
                'category_id' => $category->id,
                'category_image' => $category->category_image,
                'category_name' => $category->category_name,
                'job_post_count' => $jobPostCount
            ];
        }
        return response()->json([
            'message' => 'Category job count',
            'data' => $categoryWiseJobPosts
        ]);
    }
    public function categoryWiseJobPost(Request $request)
    {
        // Fetch all categories
        $categories = Category::all();

        // Initialize an empty array to store category-wise job posts
        $categoryWiseJobPosts = [];

        // Loop through each category
        foreach ($categories as $category) {
            // Fetch job posts related to the current category
            $jobPosts = JobPost::with('recruiter', 'user', 'category')
                ->where('category_id', $category->id)
                ->get();

            // Count the number of job posts for the current category
            $jobPostCount = $jobPosts->count();

            // Add category name and job posts to the result array
            $categoryWiseJobPosts[] = [
                'category_name' => $category->category_name,
                'job_posts' => $jobPosts,
                'job_post_count' => $jobPostCount
            ];
        }


        return response()->json([
            'message' => 'Category-wise Job List',
            'data' => $categoryWiseJobPosts
        ]);
    }
//    public function categoryWiseJobPost(Request $request)
//    {
//        // Fetch all categories
//        $categories = Category::all();
//
//        // Get the user ID
//        $user_id = $request->user()->id; // Assuming you have authentication middleware
//
//        // Initialize an empty array to store category-wise job posts
//        $categoryWiseJobPosts = [];
//
//        // Loop through each category
//        foreach ($categories as $category) {
//            // Fetch job posts related to the current category
//            $jobPosts = JobPost::with('recruiter', 'user', 'category')
//                ->where('category_id', $category->id)
//                ->get();
//
//            // Check if the job posts are bookmarked for the current user
//            $bookmarked_job_ids = Bookmark::where('user_id', $user_id)
//                ->pluck('job_post_id')
//                ->toArray();
//
//            // Iterate through job posts and set the is_bookmarked attribute
//            foreach ($jobPosts as $jobPost) {
////                $jobPost->is_bookmarked = in_array($jobPost->id, $bookmarked_job_ids);
//                if (in_array($jobPost->id, $bookmarked_job_ids)) {
//                    $jobPost->is_bookmarked = true;
//                } else {
//                    $jobPost->is_bookmarked = false;
//                }
//
//            }
//
//            // Count the number of job posts for the current category
//            $jobPostCount = $jobPosts->count();
//
//            // Add category name and job posts to the result array
//            $categoryWiseJobPosts[] = [
//                'category_name' => $category->category_name,
//                'job_posts' => $jobPosts,
//                'job_post_count' => $jobPostCount
//            ];
//        }
//
//        return response()->json([
//            'message' => 'Category-wise Job List',
//            'data' => $categoryWiseJobPosts
//        ]);
//    }

    public function SingleCategoryWiseJobPost(Request $request)
    {
        // single job details
    }

    public function categoryIdWiseJobPost(Request $request)
    {
        $category_id = $request->category_id;

        $jobPosts = JobPost::with('recruiter', 'user', 'category')
            ->where('category_id', $category_id)->where('status','published')
            ->get();

        return response()->json([
            'message' => 'Category-wise Job List',
            'data' => $jobPosts
        ]);
    }

    public function companyWiseJobPost(Request $request)
    {
        $recruiters = User::where('userType', 'RECRUITER')->paginate(9);

        $recruiterWiseJobPosts = [];
        foreach ($recruiters as $recruiter) {
            // Fetch job posts related to the current category
            $job_posts = JobPost::with('recruiter', 'user', 'category')
                ->where('user_id', $recruiter->id)->where('status','published')
                ->get();

            // Count the number of job posts for the current category
            $jobPostCount = $job_posts->count();
            $isCompanyBookmarked = false;
            foreach ($job_posts as $job_post) {
                $company_bookmarked_job_ids = CompanyBookmark::where('recruiter_id', $job_post->recruiter_id)->exists();
                $isCompanyBookmarked = $company_bookmarked_job_ids;
            }

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
                    'job_post_count' => $jobPostCount,
                    'is_company_bookmark' => $isCompanyBookmarked,
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
            'message' => 'Company Wise Job List',
            'data' => $recruiterWiseJobPosts
        ]);
    }


}
