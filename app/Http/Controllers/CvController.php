<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Portfolio;
use App\Models\Reference;
use App\Models\Resume;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CvController extends Controller
{
    //

    public function addReference(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'designation' => 'required|string',
            'organization' => 'string',
            'address' => 'string|nullable',
            'email' => 'required|email',
            'contact_no' => 'string',
            'relation' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Training instance
        $reference = new Reference();
        $reference->user_id = auth()->user()->id;
        $reference->name = $request->name;
        $reference->designation = $request->designation;
        $reference->organization = $request->organization;
        $reference->address = $request->address ?? null;
        $reference->email = $request->email;
        $reference->contact_no = $request->contact_no;
        $reference->relation = $request->relation;
        $reference->save();

        return response()->json([
            'message' => 'Reference information added successfully',
            'data' => $reference,
        ],200);
    }

    public function updateReference(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'reference_id' => 'required',
            'designation' => 'string',
            'organization' => 'string',
            'address' => 'string|nullable',
            'email' => 'email',
            'contact_no' => 'string',
            'relation' => 'string',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // Find the training by user_id and training_id
        $reference = Reference::where('user_id', auth()->user()->id)
            ->where('id', $request->reference_id)
            ->first();

        // If Reference not found, return error
        if (!$reference) {
            return response()->json([
                'message' => 'Reference not found'
            ], 404);
        }
        $reference->user_id = auth()->user()->id;
        $reference->name = $request->name ?? $reference->name;
        $reference->designation = $request->designation ?? $reference->designation;
        $reference->organization = $request->organization ?? $reference->organization;
        $reference->address = $request->address ?? $reference->address;
        $reference->email = $request->email ?? $reference->email;
        $reference->contact_no = $request->contact_no ?? $reference->contact_no;
        $reference->relation = $request->relation ?? $reference->relation;
        $reference->update();

        return response()->json([
            'message' => 'Reference information updated successfully',
            'data' => $reference,
        ],200);
    }

    public function addPortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_link' => 'string|url|unique:Portfolios',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Create a new Training instance
        $reference = new Portfolio();
        $reference->user_id = auth()->user()->id;
        $reference->portfolio_link = $request->portfolio_link;
        $reference->save();

        return response()->json([
            'message' => 'Portfolio information added successfully',
            'data' => $reference,
        ],200);
    }

    public function updatePortfolio(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'portfolio_link' => 'string|url|unique:Portfolios',
            'portfolio_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        // Find the training by user_id and portfolio_id
        $portfolio= Portfolio::where('user_id', auth()->user()->id)
            ->where('id', $request->portfolio_id)
            ->first();

        // If Reference not found, return error
        if (!$portfolio) {
            return response()->json([
                'message' => 'Portfolio not found'
            ], 404);
        }
        $portfolio->user_id = auth()->user()->id;
        $portfolio->portfolio_link = $request->portfolio_link ?? $portfolio->portfolio_link;
        $portfolio->update();

        return response()->json([
            'message' => 'Portfolio information updated successfully',
            'data' => $portfolio,
        ],200);
    }

    public function cvUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'resume' => 'required|mimes:pdf',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $resume = new Resume();
        if ($request->file('resume')) {
            $resume->resume = saveResume($request);
        }
        $resume->user_id = auth()->user()->id;
        $resume->save();
        return response()->json([
            'message' => 'Resume added Successfully',
            'data' => $resume
        ]);
    }
    public function deleteResume(Request $request)
    {
        $id = $request->id;
        $resume = Resume::where('id', $id)->first();
        if ($resume) {
            candidateRemoveImage($resume->resume);
            $resume->delete();
            return response()->json([
                'message' => 'Resume deleted successfully',
            ],200);
        }
        return response()->json([
            'message' => 'Resume Not Found',
        ],404);
    }
    public function showResume()
    {
        $resume = Resume::where('user_id',auth()->user()->id)->get();
        if (!empty($resume))
        {
            return response()->json([
                'message' => 'Resume List',
                'data' => $resume,
            ]);
        }else{
            return response()->json([
                'message' => 'No data Found',
            ],404);
        }
    }


}
