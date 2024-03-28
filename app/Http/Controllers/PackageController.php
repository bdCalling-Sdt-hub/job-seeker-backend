<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PackageController extends Controller
{
    //
    public function addPackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_name' => 'required|string|min:2|max:15|unique:packages',
            'amount' => 'required',
            'duration' => 'required',
            'post_limit' => 'required',
            'candidate_limit' => 'required',
            'feature' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $package = new Package();
        $package->package_name = $request->package_name;
        $package->amount = $request->amount;
        $package->duration = $request->duration;
        $package->post_limit = $request->post_limit;
        $package->candidate_limit = $request->candidate_limit;
        $package->feature = $request->feature;
        $package->save();
        if ($package) {
            return response()->json([
                'message' => 'package added Successfully',
                'data' => $package
            ], 200);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ], 402);

    }

    public function showPackage()
    {
        $package_list = Package::get();

        $formatted_package = $package_list->map(function ($package) {
            $package->feature = json_decode($package->feature);
            return $package;
        });

        return response()->json([
            'message' => 'success',
            'data' => $formatted_package
        ]);
    }
    public function updatePackage(Request $request)
    {
        $package = Package::where('id', $request->id)->first();
        if ($package) {
            $validator = Validator::make($request->all(), [
                'package_name' => 'string|min:2|max:20',
                'amount' => '',
                'duration' => '',
                'post_limit' => '',
                'candidate_limit' => '',
                'feature' => '',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $package->package_name = $request->package_name;
            $package->amount = $request->amount;
            $package->duration = $request->duration;
            $package->post_limit = $request->post_limit;
            $package->candidate_limit = $request->candidate_limit;
            $package->feature = $request->feature;
            $package->update();
            return response()->json([
                'message' => 'package updated successfully',
                'data' => $package,
            ]);
        } else {
            return response()->json([
                'message' => 'Package not found',
                'data' => []
            ]);
        }
    }

    public function deletePackage(Request $request)
    {
        $id = $request->id;
        $package = Package::where('id', $id)->first();
        if ($package) {
            $package->delete();
            return response()->json([
                'message' => 'Package deleted successfully',
            ]);
        }
        return response()->json([
            'message' => 'Package Not Found',
        ]);
    }
}
