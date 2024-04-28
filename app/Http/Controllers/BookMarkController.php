<?php

namespace App\Http\Controllers;

use App\Models\BookMark;
use Illuminate\Http\Request;

class BookMarkController extends Controller
{
    //
    public function toggleBookmark(Request $request)
    {
        $user_id = auth()->user()->id;
        $job_post_id = $request->job_post_id;

        $bookmark = Bookmark::where('user_id', $user_id)->where('job_post_id', $job_post_id)->first();

        if ($bookmark) {
            $bookmark->delete();
            return response()->json([
                'message' => 'Bookmark deleted'
            ]);
        } else {
            $bookmark_info = new BookMark();
            $bookmark_info->user_id = $user_id;
            $bookmark_info->job_post_id = $request->job_post_id;
            $bookmark_info->status = true;
            $bookmark_info->save();
            return response()->json([
                'message' => 'Bookmark added',
                'data' => $bookmark_info,
            ]);
        }
    }


    public function bookmarksData()
    {
        $user_id = auth()->user()->id;
        $bookmarks = Bookmark::with('user', 'job_post.recruiter')->where('user_id', $user_id)->paginate(9);
        return response()->json([
            'message' => 'Bookmarked job list',
            'data' => $bookmarks,
        ]);

    }

}
