<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Lean shape for the footer link list — title/slug only, no body. Powers
 * a dynamic footer links block so the Footer never hardcodes page names
 * and automatically picks up a new legal page (e.g. a future Cookie
 * Policy) without a frontend change.
 */
class LegalPageSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
        ];
    }
}
