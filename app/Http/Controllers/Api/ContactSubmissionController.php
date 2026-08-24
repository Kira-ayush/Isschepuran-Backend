<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactSubmissionRequest;
use App\Models\ContactSubmission;
use Illuminate\Http\JsonResponse;

class ContactSubmissionController extends Controller
{
    // POST /api/v1/contact-submissions
    public function store(ContactSubmissionRequest $request): JsonResponse
    {
        $data = $request->validated();

        ContactSubmission::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
        ]);

        return response()->json(['message' => 'Thank you — your message has been received.'], 201);
    }
}
