<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImpactStatResource;
use App\Models\ImpactStat;

class ImpactStatController extends Controller
{
    // GET /api/v1/impact-stats
    public function index()
    {
        return ImpactStatResource::collection(
            ImpactStat::published()->get()
        );
    }
}
