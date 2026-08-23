<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InitiativeResource;
use App\Models\Initiative;

class PillarController extends Controller
{
    // GET /api/v1/pillars
    // "Pillars" on the frontend = Initiatives flagged featured_on_home.
    public function index()
    {
        return InitiativeResource::collection(
            Initiative::featuredOnHome()->get()
        );
    }
}
