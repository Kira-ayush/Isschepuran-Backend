<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `SectionHeading` type exactly
 * (frontend/src/lib/types.ts).
 */
class SectionHeadingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'eyebrow' => $this->eyebrow,
            'heading' => $this->heading,
            'image' => $this->getFirstMediaUrl('image') ?: null,
            'imageAlt' => $this->image_alt,
        ];
    }
}
