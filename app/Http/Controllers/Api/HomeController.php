<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImpactStatResource;
use App\Http\Resources\InitiativeResource;
use App\Http\Resources\TestimonialResource;
use App\Models\ImpactStat;
use App\Models\Initiative;
use App\Models\Testimonial;

class HomeController extends Controller
{
    // GET /api/v1/home
    // Shape matches the Next.js `HomePageContent` type exactly
    // (frontend/src/lib/types.ts).
    public function show()
    {
        return response()->json([
            'hero' => $this->heroContent(),
            'impactStats' => ImpactStatResource::collection(
                ImpactStat::published()->get()
            ),
            'pillars' => InitiativeResource::collection(
                Initiative::featuredOnHome()->get()
            ),
            'testimonials' => TestimonialResource::collection(
                Testimonial::published()->get()
            ),
        ]);
    }

    /**
     * Hero content lives on a `pages` record in the full CMS content model
     * (project doc, Section 7). Stubbed directly here for this first
     * vertical slice — becomes a `Page::where('slug', 'home')->first()`
     * lookup once the generic Pages/Content Blocks model is built.
     */
    private function heroContent(): array
    {
        return [
            'eyebrow' => 'Reforestation · Water · Education',
            'headline' => "Restoring Earth,\nEmpowering Communities",
            'subheading' => 'A philanthropic endeavor dedicated to reforestation, water conservation, and quality education for underserved children across eastern India.',
            'primaryCtaLabel' => 'Donate Now',
            'primaryCtaHref' => '/get-involved#donate',
            'secondaryCtaLabel' => 'Our Mission',
            'secondaryCtaHref' => '/about',
            'backgroundImage' => '/images/hero-placeholder.jpg',
        ];
    }
}
