<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HomeVideoHeroResource;
use App\Models\HomeVideoHero;

class HomeVideoHeroController extends Controller
{
    // GET /api/v1/home-video-hero
    public function show()
    {
        return new HomeVideoHeroResource(HomeVideoHero::current());
    }
}
