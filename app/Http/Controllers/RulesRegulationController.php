<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Privacy;
use App\Models\TermsConditions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RulesRegulationController extends Controller
{
    public function addAboutUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'title' => 'required|unique:abouts',
                'description' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $page = new About();
            $page->title = $request->title;
            $page->description = $request->description;
            $page->save();
            return response()->json([
                'message' => 'About Us page added successfully',
                'data' => $page,
            ]);
    }

    public function updateAboutUs(Request $request, $id)
    {
        $page = About::where('id', $id)->first();
        if (!empty($page))
        {
            $validator = Validator::make($request->all(), [
                'title' => '',
                'description' => '',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->title){
                $page->title = $request->title;
            }
            if ($request->description){
                $page->description = $request->description;
            }
            $page->update();
            return response()->json([
                'message' => 'About Us page updated successfully',
                'data' => $page,
            ]);
        }else{
            return response()->json([
                'message' => 'Page is not found',
            ],404);
        }

    }

// Similar methods for Privacy Policy and Terms and Conditions

    public function addPrivacyPolicy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:abouts',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $page = new Privacy();
        $page->title = $request->title;
        $page->description = $request->description;
        $page->save();
        return response()->json([
            'message' => 'About Us page added successfully',
            'data' => $page,
        ]);
    }

    public function updatePrivacyPolicy(Request $request, $id)
    {
        $page = Privacy::where('id', $id)->first();
        if (!empty($page))
        {
            $validator = Validator::make($request->all(), [
                'title' => '',
                'description' => '',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->title){
                $page->title = $request->title;
            }
            if ($request->description){
                $page->description = $request->description;
            }
            $page->update();
            return response()->json([
                'message' => 'About Us page updated successfully',
                'data' => $page,
            ]);
        }else{
            return response()->json([
                'message' => 'Page is not found',
            ],404);
        }
    }

    public function addTermsAndConditions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:abouts',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $page = new TermsConditions();
        $page->title = $request->title;
        $page->description = $request->description;
        $page->save();
        return response()->json([
            'message' => 'About Us page added successfully',
            'data' => $page,
        ]);
    }

    public function updateTermsAndConditions(Request $request, $id)
    {
        $page = TermsConditions::where('id', $id)->first();
        if (!empty($page))
        {
            $validator = Validator::make($request->all(), [
                'title' => '',
                'description' => '',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            if ($request->title){
                $page->title = $request->title;
            }
            if ($request->description){
                $page->description = $request->description;
            }
            $page->update();
            return response()->json([
                'message' => 'About Us page updated successfully',
                'data' => $page,
            ]);
        }else{
            return response()->json([
                'message' => 'Page is not found',
            ],404);
        }
    }

//    public function
    public function aboutUs()
    {
        $about = About::get();
        if ($about) {
            return response()->json([
                'message' => 'about us',
                'data' => $about
            ], 200);
        } else {
            return response()->json([
                'message' => 'no data found',
                'data' => $about,
            ], 404);
        }
    }

    public function termsCondition()
    {
        $terms = TermsConditions::get();
        if ($terms) {
            return response()->json([
                'message' => 'Terms and Condition',
                'data' => $terms
            ], 200);
        } else {
            return response()->json([
                'message' => 'No data found',
                'data' => []
            ], 200);
        }
    }

    public function privacyPolicy()
    {
        $privacy = Privacy::get();
        if ($privacy) {
            return response()->json([
                'message' => 'Privacy and Policy',
                'data' => $privacy
            ], 200);
        } else {
            return response()->json([
                'message' => 'No data found',
                'data' => []
            ], 200);
        }
    }
}
