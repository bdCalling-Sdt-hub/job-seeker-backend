<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use App\Models\JobPost;
use App\Models\UserJobPostView;
use Illuminate\Http\Request;

class PopularJobController extends Controller
{
    //



    public function jobDetails(Request $request)
    {
        $jobId = $request->job_id;

        // Find the job post by its ID
        $jobPost = JobPost::with('user', 'recruiter', 'category')->findOrFail($jobId);

        // Increment view count and record user view
        $jobPost->increment('view_count');
        $user = auth()->user();
        if ($user) {
            UserJobPostView::create([
                'user_id' => $user->id,
                'job_post_id' => $jobId,
            ]);
        }

        // Decode JSON fields
//        $jobPost->education = json_decode($jobPost->education);
//        $jobPost->additional_requirement = json_decode($jobPost->additional_requirement);
//        $jobPost->compensation_other_benifits = json_decode($jobPost->compensation_other_benifits);
        $jobPost->key_word = json_decode($jobPost->key_word);
//        $jobPost->responsibilities = json_decode($jobPost->responsibilities);
//        if (is_string($jobPost->recruiter->company_service)) {
//            $jobPost->recruiter->company_service = json_decode($jobPost->recruiter->company_service);
//        }

        return response()->json([
            'message' => 'Job Details',
            'data' => $jobPost,
        ]);
    }

    public function popularJobPost()
    {
//        $user_id = auth()->user()->id;
        // Check if the user is authenticated
        if (auth()->user()) {
            $user_id = auth()->user()->id; // Get authenticated user id
        } else {
            $user_id = null; // For unauthenticated users
        }

        $query = JobPost::query();

        $job_posts = $query->with('user', 'recruiter', 'category')
            ->where('status', 'published')
            ->orderByDesc('view_count')
            ->paginate(9);

        $formatted_job_list = $job_posts->map(function ($job) {
            $job->key_word = json_decode($job->key_word);
            return $job;
        });

        if ($user_id !== null) {
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
        }
        return response()->json([
            'message' => 'Popular Job List',
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
}
