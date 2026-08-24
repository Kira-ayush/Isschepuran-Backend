<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImpactHeroResource;
use App\Models\ImpactHero;

class ImpactHeroController extends Controller
{
    // GET /api/v1/impact-hero
    public function show()
    {
        return new ImpactHeroResource(ImpactHero::current());
    }
}
