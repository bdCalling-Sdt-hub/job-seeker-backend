<?php

namespace App\Http\Controllers;

use App\Models\ContactEmail;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    //

    public function sendMessageToAdmin(Request $request)
    {
        $auth_user = auth()->user();
        $contact_email = new ContactEmail();
        $contact_email->user_id = $auth_user->id;
        $contact_email->subject = $request->subject;
        $contact_email->message = $request->message;
        if ($request->file('image')) {
            $contact_email->image = $this->saveImage($request);
        }
        $contact_email->save();
        return response()->json([
            'message' => 'send message to admin successfully',
            'data' => $contact_email,
        ]);
    }

    public function sendMessageToUser(Request $request)
    {
        $user_id = $request->user_id;
//        $auth_user = auth()->user();
        $contact_email = new ContactEmail();
        $contact_email->user_id = $user_id;
        $contact_email->subject = $request->subject;
        $contact_email->message = $request->message;
        if ($request->file('image')) {
            $contact_email->image = $this->saveImage($request);
        }
        $contact_email->save();
        return response()->json([
            'message' => 'send message to user successfully',
            'data' => $contact_email,
        ]);
    }
    public function showAllMessage(Request $request)
    {
        $subject = $request->subject;
        $query = ContactEmail::with('user');

        // If a subject is provided, filter messages by that subject
        if ($subject !== null) {
            $query->where('subject', 'like', '%' . $subject . '%');
        }

        $message = $query->paginate(9);

        return response()->json([
            'message' => 'Message List',
            'data' => $message,
        ]);
    }
    public function deleteMessage(Request $request)
    {
        $user_id = $request->user_id;
        $messages = ContactEmail::where('user_id', $user_id)->get();

        if ($messages){
            foreach ($messages as $message) {
                $message->delete();
            }

            return response()->json([
                'message' => 'Messages deleted successfully',
            ]);
        }else{
            return response()->json([
                'message' => 'User Not Found',
            ],404);
        }


    }


    protected function saveImage($request)
    {
        $image = $request->file('image');
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        $directory = 'adminAsset/contact-image/';
        $imgUrl = $directory . $imageName;
        $image->move($directory, $imageName);
        return $imgUrl;
    }
}
