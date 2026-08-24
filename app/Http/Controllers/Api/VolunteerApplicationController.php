<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VolunteerApplicationRequest;
use App\Models\VolunteerApplication;
use Illuminate\Http\JsonResponse;

class VolunteerApplicationController extends Controller
{
    // POST /api/v1/volunteer-applications
    public function store(VolunteerApplicationRequest $request): JsonResponse
    {
        $data = $request->validated();

        VolunteerApplication::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'country_code' => $data['countryCode'],
            'phone' => $data['phone'],
            'area_of_interest' => $data['areaOfInterest'],
            'message' => $data['message'],
        ]);

        return response()->json(['message' => 'Thank you — your application has been received.'], 201);
    }
}
