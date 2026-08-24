<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterSubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;

class NewsletterSubscriberController extends Controller
{
    // POST /api/v1/newsletter-subscribers
    public function store(NewsletterSubscribeRequest $request): JsonResponse
    {
        $data = $request->validated();

        // updateOrCreate so re-subscribing after unsubscribing (or a
        // duplicate signup) just resets status rather than erroring on
        // the unique email constraint.
        NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            ['status' => 'subscribed', 'subscribed_at' => now()]
        );

        return response()->json(['message' => 'You\'re subscribed — thank you!'], 201);
    }
}
