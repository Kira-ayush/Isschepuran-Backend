<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VolunteerApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'countryCode' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:30'],
            'areaOfInterest' => ['required', 'in:reforestation,waste_management,community_education,administrative_support'],
            'message' => ['required', 'string', 'max:5000'],
        ];
    }
}
