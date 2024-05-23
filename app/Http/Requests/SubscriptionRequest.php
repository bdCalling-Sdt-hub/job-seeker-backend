<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            //
            'user_id' => 'required',
            'tx_ref' => 'required',
            'amount' => 'required',
            'currency' => 'required',
            'payment_type' => 'required',
            'status' => 'required',
            'email' => 'required',
            'name' => 'required',
        ];
    }
}
