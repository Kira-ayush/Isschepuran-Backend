<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CarbonStatResource;
use App\Models\CarbonStat;

class CarbonStatController extends Controller
{
    // GET /api/v1/carbon-stats
    public function index()
    {
        return CarbonStatResource::collection(
            CarbonStat::published()->get()
        );
    }
}
