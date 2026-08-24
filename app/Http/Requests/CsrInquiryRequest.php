<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsrInquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'organizationName' => ['required', 'string', 'max:255'],
            'contactPerson' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'countryCode' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:30'],
            'budgetRange' => ['required', 'in:5l_10l,10l_50l,50l_plus'],
            'goals' => ['required', 'string', 'max:5000'],
        ];
    }
}
