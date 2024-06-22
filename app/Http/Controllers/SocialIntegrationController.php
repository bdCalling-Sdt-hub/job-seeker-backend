<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use Illuminate\Http\Request;

class SocialIntegrationController extends Controller
{
    //
    public function socialShare(Request $request)
    {
        $id = $request->id;
        $job = JobPost::findOrFail($id);
        $url = route('job.show', $job->id);
        $facebookShareUrl = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url);

        return response()->json(['facebook_url' => $facebookShareUrl]);
    }
}
