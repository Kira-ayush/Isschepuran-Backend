<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GeographicReachResource;
use App\Models\GeographicReach;

class GeographicReachController extends Controller
{
    // GET /api/v1/geographic-reach
    public function index()
    {
        return GeographicReachResource::collection(
            GeographicReach::published()->get()
        );
    }
}
