<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CsrFeatureResource;
use App\Models\CsrFeature;

class CsrFeatureController extends Controller
{
    // GET /api/v1/csr-features
    public function index()
    {
        return CsrFeatureResource::collection(
            CsrFeature::published()->get()
        );
    }
}
