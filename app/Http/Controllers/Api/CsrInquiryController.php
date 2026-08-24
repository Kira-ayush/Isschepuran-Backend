<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CsrInquiryRequest;
use App\Models\CsrInquiry;
use Illuminate\Http\JsonResponse;

class CsrInquiryController extends Controller
{
    // POST /api/v1/csr-inquiries
    public function store(CsrInquiryRequest $request): JsonResponse
    {
        $data = $request->validated();

        CsrInquiry::create([
            'organization_name' => $data['organizationName'],
            'contact_person' => $data['contactPerson'],
            'email' => $data['email'],
            'country_code' => $data['countryCode'],
            'phone' => $data['phone'],
            'budget_range' => $data['budgetRange'],
            'goals' => $data['goals'],
        ]);

        return response()->json(['message' => 'Thank you — your inquiry has been received.'], 201);
    }
}
