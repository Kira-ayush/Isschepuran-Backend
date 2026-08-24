<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GetInvolvedHeroResource;
use App\Models\GetInvolvedHero;

class GetInvolvedHeroController extends Controller
{
    // GET /api/v1/get-involved-hero
    public function show()
    {
        return new GetInvolvedHeroResource(GetInvolvedHero::current());
    }
}
