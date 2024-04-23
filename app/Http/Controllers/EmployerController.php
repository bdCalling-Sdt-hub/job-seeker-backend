<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Http\Request;
use File;

class EmployerController extends Controller
{
    public function create_recruiter(Request $request)
    {
        $check = Recruiter::$new_post = new Recruiter();
        $auth = auth()->user()->id;
        $new_post->user_id = $auth;
        $new_post->category_id = $request->catId;
        $new_post->sub_category_id = $request->subCatId;
        $new_post->company_name = $request->companyName;
        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('images/', $filename);
            $new_post->logo = $filename;
        }
        $new_post->phone = $request->phone;
        $new_post->location = $request->location;
        $new_post->verify_no = $request->verify_no;
        $new_post->website_url = $request->website_url;
        $new_post->year_of_establishment = $request->stablished;
        $new_post->company_size = $request->company_size;
        $new_post->linkdin_url = $request->linkdin_url;
        $new_post->facebook_url = $request->facebook_url;
        $new_post->instagram_url = $request->instagram_url;
        $new_post->company_des = $request->company_des;
        $new_post->company_service = $request->company_service;
        $new_post->country = $request->country;
        $new_post->save();
        if ($new_post) {
            return response()->json([
                'status' => 'success',
                'message' => 'Post published success',
                'data' => $new_post
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    public function show_recruiter()
    {
        $auth = auth()->user()->id;
        $information = User::with('recruiter', 'recruiter.category')->where('id', $auth)->first();

        if ($information) {
            return response()->json([
                'status' => 'success',
                'data' => $information
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'data' => []
            ], 200);
        }
    }

    public function updateLogo(Request $request)
    {
        $update_logo = Recruiter::find($request->id);
        if ($request->hasfile('logo')) {
            $file = $request->file('logo');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('images/', $filename);
            $update_logo->logo = $filename;
        }
        $update_logo->save();
        if ($update_logo) {
            return response()->json([
                'stuatus' => 'success',
                'message' => 'logo update',
                'data' => $update_logo
            ], 200);
        } else {
            return response()->json([
                'stuatus' => 'false',
                'message' => 'logo update faile'
            ], 401);
        }
    }

    public function logodestroy($id)
    {
        $logo = Recruiter::findOrFail($id);
        $imagePath = public_path("images/{$logo->logo}");

        if (is_dir($imagePath)) {
            echo 'This is a directory, not a file.';
        } else {
            if (File::exists($imagePath)) {
                unlink($imagePath);
                echo 'Image deleted successfully.';
            } else {
                echo 'Image not found.';
            }
        }
    }

    public function delete_recruiter($id)
    {
        $remove_job = Recruiter::where('id', $id)->delete();
        if ($remove_job) {
            return response()->json([
                'status' => 'success',
                'message' => 'Delete job post success',
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Internalr server error',
            ]);
        }
    }

    public function edite_recruiter($id)
    {
        $edite_job = Recruiter::where('id', $id)->first();
        if ($edite_job) {
            return response()->json([
                'status' => 'success',
                'message' => 'Edite job post',
                'data' => $edite_job
            ]);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => 'Internalr server error',
                'data' => $edite_job
            ]);
        }
    }

//    public function update_recrioter(Request $request)
//    {
//        $auth = auth()->user()->id;
//        $recruiterCount = Recruiter::where('user_id', $auth)->count();
//        $recruiterCount = Recruiter::where('user_id', $auth)->first();
//        $recruiterId = $recruiterCount->id;
//
//        if ($recruiterCount) {
//            $update_job = Recruiter::find($recruiterId);
//            $update_job->category_id = $request->catId ?? $update_user->category_id;
//            $update_job->sub_category_id = $request->subCatId ?? $update_user->sub_category_id;
//            $update_job->company_name = $request->companyName ?? $update_user->company_name;
//            $update_job->phone = $request->phone ?? $update_user->phone;
//            $update_job->location = $request->location ?? $update_user->location;
//            $update_job->verify_no = $request->verify_no ?? $update_user->verify_no;
//            $update_job->website_url = $request->website_url ?? $update_user->website_url;
//            $update_job->year_of_establishment = $request->stablished ?? $update_user->year_of_establishment;
//            $update_job->company_size = $request->company_size ?? $update_user->company_size;
//            $update_job->linkdin_url = $request->linkdin_url ?? $update_user->linkdin_url;
//            $update_job->facebook_url = $request->facebook_url ?? $update_user->facebook_url;
//            $update_job->instagram_url = $request->instagram_url ?? $update_user->instagram_url;
//            $update_job->company_des = $request->company_des ?? $update_user->company_des;
//            $update_job->company_service = $request->company_service ?? $update_user->company_service;
//            $update_job->country = $request->country ?? $update_user->country;
//            $update_job->save();
//
//            $update_user = User::find($auth);
//            $update_user->fullName = $request->companyName ?? $update_user->email;
//            $update_user->email = $request->email ?? $update_user->email;
//            $update_user->save();
//
//            if ($update_job) {
//                return response()->json([
//                    'status' => 'success',
//                    'message' => 'Update job post success',
//                    'data' => $update_job
//                ]);
//            } else {
//                return response()->json([
//                    'status' => 'false',
//                    'message' => 'Internalr server error',
//                    'data' => $update_job
//                ]);
//            }
//        } else {
//            $update_job = new Recruiter();
//            $update_job->id = $request->id;
//            $update_job->user_id = $auth;
//            $update_job->category_id = $request->catId;
//            $update_job->sub_category_id = $request->subCatId;
//            $update_job->company_name = $request->companyName;
//            $update_job->phone = $request->phone;
//            $update_job->location = $request->location;
//            $update_job->verify_no = $request->verify_no;
//            $update_job->website_url = $request->website_url;
//            $update_job->year_of_establishment = $request->stablished;
//            $update_job->company_size = $request->company_size;
//            $update_job->linkdin_url = $request->linkdin_url;
//            $update_job->facebook_url = $request->facebook_url;
//            $update_job->instagram_url = $request->instagram_url;
//            $update_job->company_des = $request->company_des;
//            $update_job->company_service = $request->company_service;
//            $update_job->country = $request->country;
//            $update_job->save();
//            if ($update_job) {
//                return response()->json([
//                    'status' => 'success',
//                    'message' => 'Update job post success',
//                    'data' => $update_job
//                ]);
//            } else {
//                return response()->json([
//                    'status' => 'false',
//                    'message' => 'Internalr server error',
//                    'data' => $update_job
//                ]);
//            }
//        }
//    }
    public function update_recrioter(Request $request)
    {
        $auth = auth()->user()->id;
<<<<<<< HEAD
        $recruiterCount = Recruiter::where('user_id', $auth)->count();
        $recruiterDetails = Recruiter::where('user_id', $auth)->first();
        $recruiterId = $recruiterDetails->id;
=======
        $recruiter = Recruiter::where('user_id', $auth)->first();
>>>>>>> 3e611a866718656e5f340f3ac5b2ed53dca27303

        if ($recruiter) {
            $update_job = $recruiter;
            $update_job->category_id = $request->catId ?? $update_job->category_id;
            $update_job->sub_category_id = $request->subCatId ?? $update_job->sub_category_id;
            $update_job->company_name = $request->companyName ?? $update_job->company_name;
            $update_job->phone = $request->phone ?? $update_job->phone;
            $update_job->location = $request->location ?? $update_job->location;
            $update_job->verify_no = $request->verify_no ?? $update_job->verify_no;
            $update_job->website_url = $request->website_url ?? $update_job->website_url;
            $update_job->year_of_establishment = $request->stablished ?? $update_job->year_of_establishment;
            $update_job->company_size = $request->company_size ?? $update_job->company_size;
            $update_job->linkdin_url = $request->linkdin_url ?? $update_job->linkdin_url;
            $update_job->facebook_url = $request->facebook_url ?? $update_job->facebook_url;
            $update_job->instagram_url = $request->instagram_url ?? $update_job->instagram_url;
            $update_job->company_des = $request->company_des ?? $update_job->company_des;
            $update_job->company_service = $request->company_service ?? $update_job->company_service;
            $update_job->country = $request->country ?? $update_job->country;
            $update_job->save();

            $update_user = User::find($auth);
            $update_user->fullName = $request->companyName ?? $update_user->fullName;
            $update_user->email = $request->email ?? $update_user->email;

            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $filename = 'images/' . time() . '.' . $extenstion;
                $file->move('images/', $filename);
                $update_user->image = $filename;
            }
            $update_user->save();
<<<<<<< HEAD
            if ($update_job) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Update job post success',
                    'data' => $update_job
                ]);
            } else {
                return response()->json([
                    'status' => 'false',
                    'message' => 'Internalr server error',
                    'data' => $update_job
                ]);
            }
=======

            return response()->json([
                'status' => 'success',
                'message' => 'Update recruiter success',
                'data' => $update_job
            ]);
>>>>>>> 3e611a866718656e5f340f3ac5b2ed53dca27303
        } else {
            $update_job = new Recruiter();
            $update_job->user_id = $auth;
            $update_job->category_id = $request->catId;
            $update_job->sub_category_id = $request->subCatId;
            $update_job->company_name = $request->companyName;
            $update_job->phone = $request->phone;
            $update_job->location = $request->location;
            $update_job->verify_no = $request->verify_no;
            $update_job->website_url = $request->website_url;
            $update_job->year_of_establishment = $request->stablished;
            $update_job->company_size = $request->company_size;
            $update_job->linkdin_url = $request->linkdin_url;
            $update_job->facebook_url = $request->facebook_url;
            $update_job->instagram_url = $request->instagram_url;
            $update_job->company_des = $request->company_des;
            $update_job->company_service = $request->company_service;
            $update_job->country = $request->country;
            $update_job->save();

            $update_user = User::find($auth);

            if ($request->hasfile('image')) {
                $file = $request->file('image');
                $extenstion = $file->getClientOriginalExtension();
                $filename = 'images/' . time() . '.' . $extenstion;
                $file->move('images/', $filename);
                $update_user->image = $filename;
            }
            $update_user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Add recruiter success',
                'data' => $update_job
            ]);
        }
    }
}
