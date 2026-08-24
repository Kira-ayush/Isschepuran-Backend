<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateDonationOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'donorName' => ['required', 'string', 'max:255'],
            'donorEmail' => ['required', 'email', 'max:255'],
            'donorPhone' => ['nullable', 'string', 'max:30'],
            'amount' => ['required', 'numeric', 'min:1'],
        ];
    }
}
