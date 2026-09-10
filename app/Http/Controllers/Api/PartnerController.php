<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;

class PartnerController extends Controller
{
    // GET /api/v1/partners
    public function index()
    {
        return PartnerResource::collection(
            Partner::published()->get()
        );
    }
}
