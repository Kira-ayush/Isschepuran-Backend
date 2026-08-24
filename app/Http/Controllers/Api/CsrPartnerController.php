<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CsrPartnerResource;
use App\Models\CsrPartner;

class CsrPartnerController extends Controller
{
    // GET /api/v1/csr-partners
    public function index()
    {
        return CsrPartnerResource::collection(
            CsrPartner::published()->get()
        );
    }
}
