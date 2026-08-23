<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `HeroContent` type exactly
 * (frontend/src/lib/types.ts).
 */
class HeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'eyebrow' => $this->eyebrow,
            'headline' => $this->headline,
            'subheading' => $this->subheading,
            'primaryCtaLabel' => $this->primary_cta_label,
            'primaryCtaHref' => $this->primary_cta_href,
            'secondaryCtaLabel' => $this->secondary_cta_label,
            'secondaryCtaHref' => $this->secondary_cta_href,
            'backgroundImage' => $this->getFirstMediaUrl('background') ?: null,
        ];
    }
}
