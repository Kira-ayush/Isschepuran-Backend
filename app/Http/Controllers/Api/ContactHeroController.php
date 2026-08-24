<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactHeroResource;
use App\Models\ContactHero;

class ContactHeroController extends Controller
{
    // GET /api/v1/contact-hero
    public function show()
    {
        return new ContactHeroResource(ContactHero::current());
    }
}
