<?php

namespace App\Http\Controllers;

use App\Models\Recruiter;
use Illuminate\Http\Request;
use File;

class EmployerController extends Controller
{
    public function create_recruiter(Request $request)
    {
        $new_post = new Recruiter();
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
        $new_post->social_media_link = $request->social_media_link;
        $new_post->company_des = $request->company_des;
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
        $information = Recruiter::where('user_id', $auth)->first();
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

    public function update_recrioter(Request $request)
    {
        $update_job = Recruiter::find($request->id);
        $update_job->id = $request->id;
        $update_job->user_id = 1;
        $update_job->category_id = $request->catId;
        $update_job->sub_category_id = $request->subCatId;
        $update_job->company_name = $request->companyName;
        $update_job->phone = $request->phone;
        $update_job->location = $request->location;
        $update_job->verify_no = $request->verify_no;
        $update_job->website_url = $request->website_url;
        $update_job->year_of_establishment = $request->stablished;
        $update_job->company_size = $request->company_size;
        $update_job->social_media_link = $request->social_media_link;
        $update_job->company_des = $request->company_des;
        $update_job->save();
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
    }
}
