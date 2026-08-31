<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HeroSlideResource;
use App\Models\HeroSlide;

class HeroSlideController extends Controller
{
    // GET /api/v1/hero-slides
    public function index()
    {
        return HeroSlideResource::collection(
            HeroSlide::published()->get()
        );
    }
}
