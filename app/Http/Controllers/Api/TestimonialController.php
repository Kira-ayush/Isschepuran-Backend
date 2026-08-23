<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestimonialResource;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    // GET /api/v1/testimonials
    public function index()
    {
        return TestimonialResource::collection(
            Testimonial::published()->get()
        );
    }
}
