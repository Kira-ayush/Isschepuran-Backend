<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutHeroResource;
use App\Models\AboutHero;

class AboutHeroController extends Controller
{
    // GET /api/v1/about-hero
    public function show()
    {
        return new AboutHeroResource(AboutHero::current());
    }
}
