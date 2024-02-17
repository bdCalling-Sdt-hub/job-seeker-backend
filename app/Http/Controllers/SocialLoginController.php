<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SocialLoginController extends Controller
{
    //

    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'email|required|max:100',
            'user_type' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Check if a user with this email exists without Google or Facebook ID
        $manual_user = User::where('email', $request->email)
            ->whereNull('google_id')
            ->whereNull('facebook_id')
            ->first();

        if ($manual_user) {
            return response()->json([
                'message' => 'User already exists. Sign in manually.',
            ], 422);
        } else {
            // Check if a user with this email exists with Google or Facebook ID
            $user = User::where('email', $request->email)
                ->where(function ($query) {
                    $query
                        ->whereNotNull('google_id')
                        ->orWhereNotNull('facebook_id');
                })
                ->first();

            if ($user) {
                if ($token = auth()->login($user)) {
                    return $this->responseWithToken($token);
                }
                return response()->json([
                    'message' => 'User unauthorized'
                ], 401);
            } else {
                $avatar = 'dummyImg/default.jpg';
                // Create a new user
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->user_type = $request->user_type;
                $user->google_id = $request->google_id ?? null;
                $user->facebook_id = $request->facebook_id ?? null;
                $user->latitude = $request->latitude ?? null;
                $user->longitude = $request->latitude ?? null;
                $user->is_verified = 1;
                $user->image = $avatar;
                $user->save();
                // Generate token for the new user
                if ($token = auth()->login($user)) {
                    return $this->responseWithToken($token);
                }
                return response()->json([
                    'message' => 'User unauthorized'
                ], 401);
            }
        }
    }
}
