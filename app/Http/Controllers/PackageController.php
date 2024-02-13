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
            'word_limit' => 'required',
            'image_limit' => 'required',
            'feature' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $package = new Package();
        $package->package_name = $request->package_name;
        $package->amount = $request->amount;
        $package->duration = $request->duration;
        $package->word_limit = $request->word_limit;
        $package->image_limit = $request->image_limit;
        $package->feature = $request->feature;
        $package->save();
        if ($package){
            return response()->json([
                'message' => 'package added Successfully',
                'data' => $package
            ],200);
        }
        return response()->json([
            'message' => 'Something went wrong',
        ],402);

    }
}
