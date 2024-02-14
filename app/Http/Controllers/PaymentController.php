<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
class PaymentController extends Controller
{
    //
    public function paypalPayment(Request $request)
    {
        $provider = new PayPalClient;
        $provider = \PayPal::setProvider();
        $access_token = $provider->getAccessToken();

        return response()->json([
            'message' => 'success',
            'data' => $provider,
            'access_token' => $access_token,
        ]);

    }
}
