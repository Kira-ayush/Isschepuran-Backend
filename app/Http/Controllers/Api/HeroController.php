<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HeroResource;
use App\Models\Hero;

class HeroController extends Controller
{
    // GET /api/v1/hero
    public function show()
    {
        return new HeroResource(Hero::current());
    }
}
