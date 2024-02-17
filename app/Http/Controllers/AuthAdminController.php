<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthAdminController extends Controller
{
    public function addAdmin(Request $request)
    {
        $validatedData = $request->validate([
            'fullName' => 'required|string|min:2',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'userType' => 'string',
        ]);

        $validatedData['userType'] = 'ADMIN';
        $user = new User();
        $user->fullName = $request->fullName;
        $user->email = $request->email;
        $user->userType = $validatedData['userType'];
        $user->otp = 0;
        $user->verify_email = 1;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Admin added successfully',
            'user' => $user, // Optionally, you can return the created user data
        ], 200);
    }

}
