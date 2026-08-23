<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CtaBandResource;
use App\Models\CtaBand;

class CtaBandController extends Controller
{
    // GET /api/v1/cta-band
    public function show()
    {
        return new CtaBandResource(CtaBand::current());
    }
}
