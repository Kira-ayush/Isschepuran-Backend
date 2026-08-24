<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InitiativeListResource;
use App\Models\Initiative;
use Illuminate\Http\Response;

class InitiativeController extends Controller
{
    // GET /api/v1/initiatives
    // All published initiatives (not just Home's featured_on_home 3) —
    // powers the full Initiatives page listing.
    public function index()
    {
        return InitiativeListResource::collection(
            Initiative::with('category')->published()->get()
        );
    }

    // GET /api/v1/initiatives/{slug}
    // Powers the /initiatives/{slug} detail page.
    public function show(string $slug)
    {
        $initiative = Initiative::with('category')->published()->where('slug', $slug)->first();

        if (! $initiative) {
            return response()->json(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        return new InitiativeListResource($initiative);
    }
}
