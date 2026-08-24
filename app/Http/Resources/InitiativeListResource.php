<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Fuller shape than InitiativeResource (which powers Home's lean Pillar
 * cards) — includes `body`, the full detail text shown on the Initiatives
 * page. Kept as a separate Resource rather than adding `body` to
 * InitiativeResource so Home's /pillars contract doesn't gain an unused
 * field.
 */
class InitiativeListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'category' => new CategoryResource($this->category),
            'summary' => $this->summary,
            'body' => $this->body,
            'image' => $this->getFirstMediaUrl('image') ?: null,
            'icon' => $this->icon,
            'order' => $this->order,
        ];
    }
}
