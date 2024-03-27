<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Privacy;
use App\Models\TermsConditions;
use Illuminate\Http\Request;

class RulesRegulationController extends Controller
{
    //
    public function aboutUs()
    {
        $about = About::first();
        if ($about) {
            return response()->json([
                'message' => 'about us',
                'data' => $about
            ], 200);
        } else {
            return response()->json([
                'message' => 'no data found',
                'data' => []
            ], 200);
        }
    }

    public function termsCondition()
    {
        $terms = TermsConditions::first();
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
        $privacy = Privacy::first();
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
