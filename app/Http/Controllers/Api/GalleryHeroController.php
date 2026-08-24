<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryHeroResource;
use App\Models\GalleryHero;

class GalleryHeroController extends Controller
{
    // GET /api/v1/gallery-hero
    public function show()
    {
        return new GalleryHeroResource(GalleryHero::current());
    }
}
