<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DonationMethodResource;
use App\Models\DonationMethod;

class DonationMethodController extends Controller
{
    // GET /api/v1/donation-methods
    public function index()
    {
        return DonationMethodResource::collection(
            DonationMethod::published()->get()
        );
    }
}
