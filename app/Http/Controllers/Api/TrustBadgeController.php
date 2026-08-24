<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TrustBadgeResource;
use App\Models\TrustBadge;

class TrustBadgeController extends Controller
{
    // GET /api/v1/trust-badges
    public function index()
    {
        return TrustBadgeResource::collection(
            TrustBadge::published()->get()
        );
    }
}
