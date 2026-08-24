<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SdgAlignmentResource;
use App\Models\SdgAlignment;

class SdgAlignmentController extends Controller
{
    // GET /api/v1/sdg-alignments
    public function index()
    {
        return SdgAlignmentResource::collection(
            SdgAlignment::published()->get()
        );
    }
}
