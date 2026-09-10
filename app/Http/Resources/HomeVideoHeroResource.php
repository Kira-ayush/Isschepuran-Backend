<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `HomeVideoHeroContent` type exactly
 * (frontend/src/lib/types.ts). CTAs are filtered here to only complete
 * label+link pairs so the frontend can just map over whatever it gets.
 */
class HomeVideoHeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'enabled' => (bool) $this->is_enabled,
            'eyebrow' => $this->eyebrow,
            'headline' => $this->headline,
            'subheading' => $this->subheading,
            'video' => $this->getFirstMediaUrl('video') ?: null,
            'videoUrl' => $this->video_url ?: null,
            'poster' => $this->getFirstMediaUrl('poster') ?: null,
            'posterAlt' => $this->poster_alt,
            'overlay' => (bool) $this->overlay,
            'ctas' => collect([1, 2, 3])
                ->map(fn ($i) => [
                    'label' => $this->{"cta{$i}_label"},
                    'href' => $this->{"cta{$i}_href"},
                ])
                ->filter(fn ($cta) => filled($cta['label']) && filled($cta['href']))
                ->values(),
        ];
    }
}
