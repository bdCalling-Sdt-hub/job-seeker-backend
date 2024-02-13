<?php

namespace App\Http\Controllers\Api\Addmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function package()
    {
        $pakege = Package::get();

        if ($pakege) {
            return response()->json([
                'status' => 'success',
                'data' => $pakege
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }

    public function userList(Request $request)
    {
        $package_id = $request->id;

        // Basic user//

        $basic_pakege = Package::where('package_name', 'Quater Page')->first();
        $basic_pakage_id = $basic_pakege->id;
        $basic_subcription = Subscription::where('package_id', $basic_pakage_id)->count();

        // Premium user //

        $premium_pakege = Package::where('package_name', 'Half Page')->first();
        $premium_pakage_id = $premium_pakege->id;
        $premium_subcription = Subscription::where('package_id', $premium_pakage_id)->count();

        // Gold user//

        $gold_pakege = Package::where('package_name', 'Half Page')->first();
        $gold_pakage_id = $gold_pakege->id;
        $gold_subcription = Subscription::where('package_id', $premium_pakage_id)->count();

        // Total user //

        $total_user = User::count();

        if ($package_id == 0) {
            $subscribe_user = Subscription::with('user', 'package')->orderBy('id', 'desc')->paginate(10);
        } else {
            $subscribe_user = Subscription::where('package_id', $package_id)->with('user', 'package')->orderBy('id', 'desc')->paginate(10);
        }

        if ($subscribe_user) {
            return response()->json([
                'status' => 'success',
                'total_user' => $total_user,
                'quater_page' => $basic_subcription,
                'half_page' => $premium_subcription,
                'full_page' => $gold_subcription,
                'data' => $subscribe_user
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ], 200);
        }
    }

    public function userDetails($id)
    {
        $user_details = Subscription::where('id', $id)->with('user', 'package')->first();

        if ($user_details) {
            return response()->json([
                'status' => 'success',
                'data' => $user_details,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ], 200);
        }
    }

    public function search_subscriber(Request $request)
    {
        $search = Subscription::with('user', 'package')
            ->whereHas('user', function ($query) use ($request) {
                $query->where('fullName', 'like', '%' . $request->name . '%');
            })
            ->get();

        if ($search) {
            return response()->json([
                'status' => 'success',
                'data' => $search
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }
}
