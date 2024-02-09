<?php

namespace App\Http\Controllers\Api\Webapi;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contact(ContactRequest $request)
    {
        $postContact = new Contact();
        $postContact->full_name = $request->fullName;
        $postContact->phone = $request->phone;
        $postContact->email = $request->email;
        $postContact->address = $request->address;
        $postContact->message = $request->message;
        $postContact->save();
        if ($postContact) {
            return response()->json([
                'status' => 'success',
                'message' => ' Success submit your contact form'
            ], 200);
        } else {
            return response()->json([
                'status' => 'false',
                'message' => ' Internal server error'
            ], 500);
        }
    }
}
