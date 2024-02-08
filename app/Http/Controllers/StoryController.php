<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoryController extends Controller
{
    //

    public function addStory(Request $request){

        $validator = Validator::make($request->all(),[
            'user_id' => '',
            'category_id' => '',
            'subscription_id' => '',
            'story_title' => '',
            'story_image.*' => 'required|mimes:jpeg,png,jpg,gif,svg',
            'music' => '',
            'music_type' => '',
            'description' => '',
            'story_status' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),400);
        }
        $subscription_id = $request->subscription_id;
        $subscription_details = Subscription::with('package')->where('id',$subscription_id)->first();
        $package_id = $subscription_details['package']['id'];
        $amount = $subscription_details['package']['amount'];
        $word_limit = $subscription_details['package']['word_limit'];
        $image_limit = $subscription_details['package']['image_limit'];

        if ($package_id == 1) {
            $wordLimit = $word_limit;
            $imageLimit = $image_limit;
        } elseif ($package_id == 2) {
            $wordLimit = $word_limit;
            $imageLimit = $image_limit;
        } elseif ($package_id == 3) {
            $wordLimit = $word_limit;
            $imageLimit = $image_limit;
        }
        // Validate description length based on word limit
        $descriptionLength = str_word_count($request->description);
        if ($descriptionLength > $wordLimit) {
            return response()->json(['message' => 'Description exceeds word limit for this subscription.'], 400);
        }

        // Validate image count based on image limit
        if (count($request->file('story_image')) > $imageLimit) {
            return response()->json(['message' => 'Number of images exceeds limit for this subscription.'], 400);
        }

        $story = new Story();
        $story->user_id = $request->user_id;
        $story->category_id = $request->category_id;
        $story->subscription_id = $request->subscription_id;
        $story->story_title = $request->story_title;
        $story->music_type = $request->music_type;
        $story->description = $request->description;
        $story_music = array();
        if($request->hasFile('music')) {
            foreach ($request->file('music') as $music) {
                $musicName = time() . '.' . $music->getClientOriginalExtension();
                $music->move(public_path('music'), $musicName);
                $path = '/music/' . $musicName;
                $story_music[] = $path;
            }
        }

        $story_image = array();
        if ($request->hasFile('story_image')) {
            foreach ($request->file('story_image') as $image) {
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('story-image'), $imageName);
                $path = '/story-image/' . $imageName;
                $story_image[] = $path;
            }
        }
        $story->music = json_encode($story_music);
        $story->story_image = json_encode($story_image,true);
        $story->save();
        return response()->json([
            'message' => 'Story add successfully',
            'data' => $story,
            'music' => json_decode($story['music']),
            'image' => json_decode($story['story_image'])
        ],200);
    }

    public function showStory()
    {
//        $stories = Story::all();
//        return response()->json($stories);
//        $story_list = [];
//        foreach($stories as $story){
//            $story_list = [
//                ''
//            ]
//        }

    }
}
