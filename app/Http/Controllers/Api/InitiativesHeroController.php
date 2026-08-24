<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\InitiativesHeroResource;
use App\Models\InitiativesHero;

class InitiativesHeroController extends Controller
{
    // GET /api/v1/initiatives-hero
    public function show()
    {
        return new InitiativesHeroResource(InitiativesHero::current());
    }
}
