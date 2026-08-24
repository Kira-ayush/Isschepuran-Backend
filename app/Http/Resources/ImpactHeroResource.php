<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `ImpactHeroContent` type exactly
 * (frontend/src/lib/types.ts).
 */
class ImpactHeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'headline' => $this->headline,
            'subheading' => $this->subheading,
        ];
    }
}
