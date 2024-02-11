<?php

namespace App\Http\Controllers\Api\Addmin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userList()
    {
        // Basic user//

        $basic_pakege = Package::where('package_name', 'Qarator Page')->first();
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

        $user_list = User::where('user_status', 1)->get();
    }
}
