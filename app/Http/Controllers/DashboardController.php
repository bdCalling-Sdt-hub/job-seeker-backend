<?php

namespace App\Http\Controllers;

use App\Jobs\ReportMailJob;
use App\Jobs\SendMailJob;
use App\Models\Apply;
use App\Models\ContactEmail;
use App\Models\JobPost;
use App\Models\Package;
use App\Models\Recruiter;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function monthWiseEmployer($year)
    {
        // Get current year's monthly employer count
        $currentYearEmployerCounts = User::where('userType', 'RECRUITER')
            ->whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('count', 'month');

        // Get previous year's monthly employer count
        $previousYear = $year - 1;
        $previousYearEmployerCounts = User::where('userType', 'RECRUITER')
            ->whereYear('created_at', $previousYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('count', 'month');

        $monthlyData = [];

        // Iterate through each month
        for ($month = 1; $month <= 12; $month++) {
            $currentCount = $currentYearEmployerCounts[$month] ?? 0;
            $previousCount = $previousYearEmployerCounts[$month] ?? 0;

            // Calculate growth percentage
            $growth = $previousCount != 0 ? (($currentCount - $previousCount) / $previousCount) * 100 : 100;

            // Build monthly data
            $monthlyData[$month] = [
                'current_year_count' => $currentCount,
                'previous_year_count' => $previousCount,
                'growth' => $growth
            ];
        }

        // Formatting data for target output
        $formattedData = [];
        foreach ($monthlyData as $month => $data) {
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            $formattedData[$monthName] = $data['current_year_count'];
        }

        // Calculate yearly growth
        $yearlyGrowth = $previousYearEmployerCounts->sum() != 0 ? (($currentYearEmployerCounts->sum() - $previousYearEmployerCounts->sum()) / $previousYearEmployerCounts->sum()) * 100 : 100;

        // Return response
        return response()->json([
            'message' => 'Month wise employer count for year '.$year,
            'data' => $formattedData,
            'yearly_growth' => $yearlyGrowth
        ]);
    }

    public function monthWiseJobPost($year)
    {
        // Get current year's monthly job post count
        $currentYearJobPostCounts = JobPost::whereYear('created_at', $year)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('count', 'month');

        // Get previous year's monthly job post count
        $previousYear = $year - 1;
        $previousYearJobPostCounts = JobPost::whereYear('created_at', $previousYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupByRaw('MONTH(created_at)')
            ->get()
            ->pluck('count', 'month');

        $monthlyData = [];

        // Iterate through each month
        for ($month = 1; $month <= 12; $month++) {
            $currentCount = $currentYearJobPostCounts[$month] ?? 0;
            $previousCount = $previousYearJobPostCounts[$month] ?? 0;

            // Calculate growth percentage
            $growth = $previousCount != 0 ? (($currentCount - $previousCount) / $previousCount) * 100 : 100;

            // Build monthly data
            $monthlyData[$month] = [
                'current_year_count' => $currentCount,
                'previous_year_count' => $previousCount,
                'growth' => $growth
            ];
        }

        // Formatting data for target output
        $formattedData = [];
        foreach ($monthlyData as $month => $data) {
            $monthName = date('F', mktime(0, 0, 0, $month, 1));
            $formattedData[$monthName] = $data['current_year_count'];
        }

        // Calculate yearly growth
        $yearlyGrowth = $previousYearJobPostCounts->sum() != 0 ? (($currentYearJobPostCounts->sum() - $previousYearJobPostCounts->sum()) / $previousYearJobPostCounts->sum()) * 100 : 100;

        // Return response
        return response()->json([
            'message' => 'Month wise job post count for year '.$year,
            'data' => $formattedData,
            'yearly_growth' => $yearlyGrowth
        ]);
    }
    public function dashboard(){
        $total_employers = User::where('userType','RECRUITER')->where('verify_email','1')->count();
        $total_job_post = JobPost::count();
        $total_apply_count = Apply::count();
        $subscriptions = Subscription::where('manual_status','accept')->get();
        $total_amount = 0;
        foreach ($subscriptions as $subscription) {
            $total_amount += $subscription->amount;
        }
        return response()->json([
            'total_employers' => $total_employers,
            'total_job_post' => $total_job_post,
            'total_applier' => $total_apply_count,
            'total_earning' => $total_amount,
        ]);
    }

    public function employerList(Request $request)
    {
        $status = $request->input('status');
        $categoryName = $request->input('category_name');

        $query = Recruiter::with('user', 'category');

        if ($status) {
            $query->where('status', $status);
        }

        if ($categoryName) {
            $query->whereHas('category', function ($query) use ($categoryName) {
                $query->where('category_name', $categoryName);
            });
        }

        $employerList = $query->paginate(9);

        return response()->json([
            'message' => 'Employer List',
            'data' => $employerList
        ]);
    }
    public function companyWiseSubscription(Request $request)
    {
        // Recruiter id / company id
        $user_id = $request->user_id;
        $recruiter_wise_subscription_list = [];
        $recruiter_wise_subscription = Recruiter::with('user', 'category')->where('user_id', $user_id)->first();
        $subscription = Subscription::with('package')->where('user_id', $user_id)->get();

        // Assuming each JobPost belongs to a Package
        $jobPostsQuery = JobPost::with('package')->where('user_id', $user_id);

        // Filter by package_id if provided
        if ($request->has('package_id')) {
            $jobPostsQuery->where('package_id', $request->package_id);
        }

        // Get the filtered job posts
        $jobPosts = $jobPostsQuery->get();

        // Grouping job posts by package
        $jobPostsByPackage = $jobPosts->groupBy('package_id');

        // Formatted data for subscription-wise job list
        $subscriptionJobList = collect([]);
        foreach ($jobPostsByPackage as $packageId => $jobPosts) {
            $package = Package::find($packageId);
            $subscriptionJobList->push([
                'package' => $package,
                'job_posts' => $jobPosts
            ]);
        }

        // Paginate subscription job list
        $perPage = 10; // Change the number according to your preference
        $currentPage = $request->query('page', 1);
        $currentItems = $subscriptionJobList->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $subscriptionJobList = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($subscriptionJobList), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        // Build response
        $recruiter_wise_subscription_list = [
            'company_details' => $recruiter_wise_subscription,
            'subscription' => $subscriptionJobList
        ];

        return response()->json([
            'message' => 'recruiter wise subscription list',
            'data' => $recruiter_wise_subscription_list
        ]);
    }

//    public function companyWiseSubscription(Request $request)
//    {
//        // Recruiter id / company id
//        $user_id = $request->user_id;
//        $recruiter_wise_subscription_list = [];
//        $recruiter_wise_subscription = Recruiter::with('user', 'category')->where('user_id', $user_id)->first();
//        $subscription = Subscription::with('package')->where('user_id', $user_id)->get();
//
//        // Assuming each JobPost belongs to a Package
//        $jobPosts = JobPost::with('package')->get();
//
//        // Grouping job posts by package
//        $jobPostsByPackage = $jobPosts->groupBy('package_id');
//
//        // Formatted data for subscription-wise job list
//        $subscriptionJobList = collect([]);
//        foreach ($jobPostsByPackage as $packageId => $jobPosts) {
//            $package = Package::find($packageId);
//            $subscriptionJobList->push([
//                'package' => $package,
//                'job_posts' => $jobPosts
//            ]);
//        }
//
//        // Paginate subscription job list
//        $perPage = 10; // Change the number according to your preference
//        $currentPage = $request->query('page', 1);
//        $currentItems = $subscriptionJobList->slice(($currentPage - 1) * $perPage, $perPage)->all();
//        $subscriptionJobList = new \Illuminate\Pagination\LengthAwarePaginator($currentItems, count($subscriptionJobList), $perPage, $currentPage, [
//            'path' => $request->url(),
//            'query' => $request->query(),
//        ]);
//
//        // Build response
//        $recruiter_wise_subscription_list = [
//            'company_details' => $recruiter_wise_subscription,
//            'subscription' => $subscriptionJobList
//        ];
//
//        return response()->json([
//            'message' => 'recruiter wise subscription list',
//            'data' => $recruiter_wise_subscription_list
//        ]);
//    }


    public function blockRecruiter(Request $request)
    {
        // block recruiter according to recruiter id
        $recruiter_id = $request->recruiter_id;
        $recruiter = Recruiter::where('id',$recruiter_id)->first();
        if($recruiter){
            $recruiter->status = 'blocked';
            $recruiter->update();
            return response()->json([
                'message' => 'Recruiter is blocked successfully',
                'data' => $recruiter,
            ],200);
        }else{
            return response()->json([
                'message' => 'No recruiter found',
                'data' => [],
            ],200);
        }

    }

    public function jobList(Request $request)
    {
        // Get query parameters
        $status = $request->query('status');
        $search = $request->query('search');

        // Query builder for job posts
        $query = JobPost::with('recruiter', 'user', 'category', 'subscription');

        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('job_title', 'like', "%$search%")
                    ->orWhere('area', 'like', "%$search%")
                    ->orWhere('education', 'like', "%$search%")
                    ->orWhere('key_word', 'like', "%$search%");
            });
        }

        // Paginate the results
        $job_list = $query->paginate(9);

        // Format job list
        $formatted_job_list = $job_list->map(function ($job) {
            $job->education = json_decode($job->education);
            $job->additional_requirement = json_decode($job->additional_requirement);
            $job->compensation_other_benifits = json_decode($job->compensation_other_benifits);
            $job->key_word = json_decode($job->key_word);
            $job->responsibilities = json_decode($job->responsibilities);
            return $job;
        });

        // Return response
        return response()->json([
            'message' => 'Employer List',
            'data' => [
                'current_page' => $job_list->currentPage(),
                'data' => $formatted_job_list,
                'first_page_url' => $job_list->url(1),
                'from' => $job_list->firstItem(),
                'last_page' => $job_list->lastPage(),
                'last_page_url' => $job_list->url($job_list->lastPage()),
                'links' => $job_list->links(),
                'next_page_url' => $job_list->nextPageUrl(),
                'path' => $job_list->url($job_list->currentPage()),
                'per_page' => $job_list->perPage(),
                'prev_page_url' => $job_list->previousPageUrl(),
                'to' => $job_list->lastItem(),
                'total' => $job_list->total(),
            ]
        ]);
    }

    public function jobDetails(Request $request)
    {

        $job_id = $request->id;

        // Query builder for job posts
        $query = JobPost::with('recruiter', 'user', 'category', 'subscription')->where('id',$job_id);

        // Paginate the results
        $job_list = $query->paginate(9);

        // Format job list
        $formatted_job_list = $job_list->map(function ($job) {
            $job->key_word = json_decode($job->key_word);
            return $job;
        });

        // Return response
        return response()->json([
            'message' => 'Job Details',
            'data' => $formatted_job_list,

        ]);
    }

    public function approveJobPost(Request $request)
    {
        $job_id = $request->job_id;
        $job_info = JobPost::where('id',$job_id)->first();
        if (!empty($job_info) && $job_info->status == 'pending')
        {
            $recruiterUser = User::where('id',$job_info->user_id)->first();

            $job_info->status = 'published';
            $job_info->update();
            $result = app('App\Http\Controllers\NotificationController')->sendRecruiterNotification('Job Post approved successfully',$job_info->updated_at,$recruiterUser->fullName,$recruiterUser);
            return response()->json([
                'message' => 'post is published successfully',
                'data' => $job_info,
                'notification' => $result,
            ]);
        }else{
            return response()->json([
                'message' => 'Job post not found',
                'data' => []
            ]);
        }
    }

    public function reportEmployer(Request $request)
    {

        $subject = $request->subject;
        $message = $request->message;
        $user_id = $request->user_id;
        $recruiter = User::where('id',$user_id)->first();
        $email = $recruiter->email;
        if ($email){
            dispatch(new ReportMailJob($email,$subject,$message));
            $recruiter = Recruiter::where('user_id',$recruiter->id)->first();
            $recruiterUser = User::where('id',$recruiter->user_id)->first();
            $recruiter->status = 'reported';
            $recruiter->update();
            $result = app('App\Http\Controllers\NotificationController')->sendRecruiterNotification('The job has been reported',$recruiter->updated_at,$recruiterUser->name ,$recruiterUser);
            return response()->json([
                'message' => 'Report Employer Successfully',
                'notification'=>$result,
            ]);
        }else {
            return response()->json([
                'message' => 'Employer Not Found',
            ],404);
        }
    }

    public function packageWiseCompanySubscription(Request $request)
    {
        $subscription = JobPost::with('package','subscription','recruiter')->get();
        if(!empty($subscription))
        {
            return response()->json([
                'message' => 'Total Subscribers',
                'data' => $subscription,
            ]);
        }else{
            return response()->json([
                'message' => 'Total Subscribers',
                'data' => [],
            ]);
        }
    }


}
