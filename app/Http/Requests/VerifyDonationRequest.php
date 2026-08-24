<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donationId' => ['required', 'string'],
            'razorpayOrderId' => ['required', 'string'],
            'razorpayPaymentId' => ['required', 'string'],
            'razorpaySignature' => ['required', 'string'],
        ];
    }
}
