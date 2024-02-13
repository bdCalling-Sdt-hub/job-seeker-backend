<?php

namespace App\Http\Controllers\Api\Addmin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Story;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon;
use DB;

class DashboardController extends Controller
{
    public function count_category_story()
    {
        $categories = Category::get();
        $categoryData = [];

        foreach ($categories as $category) {
            $storyCount = Story::where('category_id', $category->id)->count();
            $categoryData[] = [
                'category_name' => $category->category_name,
                'story_count' => $storyCount
            ];
        }

        echo json_encode($categoryData);
    }

    public function recent_transection()
    {
        $subscrition = Subscription::where('status', 1)->with('user', 'package')->orderBy('id', 'desc')->paginate(8);
        if ($subscrition) {
            return response()->json([
                'status' => 'success',
                'data' => $subscrition
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }

    public function transetion_details($id)
    {
        $subscrition = Subscription::where('status', 1)->where('id', $id)->with('user', 'package')->first();
        if ($subscrition) {
            return response()->json([
                'status' => 'success',
                'data' => $subscrition
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }

    // public function income()
    // {
    //     $total_income = Subscription::sum('amount');

    //     $dailyEarning = Subscription::whereDate('created_at', Carbon::today())
    //         ->select(DB::raw('SUM(amount) as dayly_income'))
    //         ->get();

    //     // WEEKLY TOTAL INCOME //

    //     $weeklyTotalSum = Subscription::select(
    //         DB::raw('(SUM(amount)) as total_amount')
    //     )
    //         ->whereYear('created_at', date('Y'))
    //         ->get()
    //         ->sum('total_amount');

    //     // MONTHLY TOTAL INCOME //

    //     $monthlySumAmount = Subscription::whereYear('created_at', date('Y'))
    //         ->whereMonth('created_at', date('n'))
    //         ->sum('amount');

    //     // YEARLY TOTAL INCOME //

    //     $yearlySumAmount = Subscription::whereYear('created_at', date('Y'))
    //         ->sum('amount');

    //     return response()->json([
    //         'total_income' => $total_income,
    //         'daily_income' => $dailyEarning,
    //         'weekly_income' => $weeklyTotalSum,
    //         'monthly_income' => $monthlySumAmount,
    //         'yearly_income' => $yearlySumAmount
    //     ]);
    // }

    public function income()
    {
        $total_income = Subscription::sum('amount');

        $dailyEarning = Subscription::whereDate('created_at', Carbon::today())
            ->select(DB::raw('SUM(amount) as daily_income'))
            ->first()
            ->daily_income ?? 0;

        // WEEKLY TOTAL INCOME //

        $weeklyTotalSum = Subscription::select(
            DB::raw('(SUM(amount)) as weekly_income')
        )
            ->whereYear('created_at', Carbon::now()->year)
            ->get()
            ->sum('weekly_income');

        // MONTHLY TOTAL INCOME //

        $monthlySumAmount = Subscription::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        // YEARLY TOTAL INCOME //

        $yearlySumAmount = Subscription::whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        

        return response()->json([
            'total_income' => $total_income,
            'daily_income' => $dailyEarning,
            'weekly_income' => $weeklyTotalSum,
            'monthly_income' => $monthlySumAmount,
            'yearly_income' => $yearlySumAmount
        ]);
    }
}
