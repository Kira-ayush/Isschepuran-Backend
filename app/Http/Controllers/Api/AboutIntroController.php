<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutIntroResource;
use App\Models\AboutIntro;

class AboutIntroController extends Controller
{
    // GET /api/v1/about-intro
    public function show()
    {
        return new AboutIntroResource(AboutIntro::current());
    }
}
