<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use App\Models\Category;
use App\Models\JobPost;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function jobFilter(Request $request)
    {
        $query = JobPost::query();

        if ($request->has('job_title')) {
            $query->where('job_title', 'like', '%' . $request->input('job_title') . '%');
        }

        if ($request->has('key_word')) {
            $query->where('key_word','like', '%' . $request->input('key_word'));
        }

        if ($request->has('job_type')) {
            $query->where('job_type', $request->input('job_type'));
        }

        if ($request->has('work_type')) {
            $query->where('work_type', $request->input('work_type'));
        }

        if ($request->has('work_category')) {
            $query->where('category_id', $request->input('work_category'));
        }

        if ($request->has('experience')) {
            $query->where('experience', '<=', $request->input('experience'));
        }

        if ($request->has('area')) {
            $query->where('area', $request->input('area'));
        }

        $job_posts = $query->with('user', 'recruiter','category')->whereIn('status', ['published','pending'])->paginate();

        return response()->json([
            'message' => 'Filtered Job List',
            'data' => $job_posts,
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
        $recruiters = User::where('userType', 'RECRUITER')->get();

        $recruiterWiseJobPosts = [];
        foreach ($recruiters as $recruiter) {
            // Fetch job posts related to the current category
            $jobPosts = JobPost::with('recruiter', 'user', 'category')
                ->where('user_id', $recruiter->id)->where('status','published')
                ->get();

            // Count the number of job posts for the current category
            $jobPostCount = $jobPosts->count();

            $recruiterWiseJobPosts[] = [
                'job_posts' => $jobPosts,
                'job_post_count' => $jobPostCount
            ];
        }
        return response()->json([
            'message' => 'Category-wise Job List',
            'data' => $recruiterWiseJobPosts
        ]);
    }

}
