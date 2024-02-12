<?php

namespace App\Http\Controllers\Api\Addmin;

use App\Http\Controllers\Controller;
use App\Models\Story;
use Illuminate\Http\Request;

class AdminStoryController extends Controller
{
    public function user_story(Request $request)
    {
        $category = $request->catId;
        if ($category) {
            $user_story = Story::where('category_id', $category)->where('story_status', 1)->orderBy('id', 'desc')->paginate(10);
        } else {
            $user_story = Story::where('story_status', 1)->orderBy('id', 'desc')->paginate(10);
        }

        if ($user_story->count() > 0) {
            $user_story->transform(function ($story) {
                $story->story_image = json_decode($story->story_image, true);
                return $story;
            });
            return response()->json([
                'status' => 'success',
                'data' => $user_story
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }

    public function userRequest()
    {
        $user_story = Story::where('story_status', 0)->orderBy('id', 'desc')->paginate(10);

        if ($user_story->count() > 0) {
            $user_story->transform(function ($story) {
                $story->story_image = json_decode($story->story_image, true);
                return $story;
            });
            return response()->json([
                'status' => 'success',
                'data' => $user_story
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => []
            ]);
        }
    }

    public function story_status(Request $request)
    {
        $update_status = Story::find($request->id);
        $update_status->story_status = $request->status;
        $update_status->save();
        if ($update_status) {
            return response()->json([
                'status' => 'success',
                'message' => 'story update success',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'story update faile',
            ], 402);
        }
    }
}
