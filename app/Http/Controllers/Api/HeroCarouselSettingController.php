<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HeroCarouselSettingResource;
use App\Models\HeroCarouselSetting;

class HeroCarouselSettingController extends Controller
{
    // GET /api/v1/hero-carousel-settings
    public function show()
    {
        return new HeroCarouselSettingResource(HeroCarouselSetting::current());
    }
}
