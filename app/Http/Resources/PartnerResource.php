<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `Partner` type exactly
 * (frontend/src/lib/types.ts). The two row labels are NOT here — they're
 * editable section headings (keys 'partners-partnership' /
 * 'partners-implemented-for'), fetched separately by the frontend.
 */
class PartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'group' => $this->group,
            'logo' => $this->getFirstMediaUrl('logo') ?: null,
            'logoAlt' => $this->logo_alt ?: $this->name,
            'order' => $this->order,
        ];
    }
}
