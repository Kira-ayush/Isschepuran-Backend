<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `SiteSettings` type exactly
 * (frontend/src/lib/types.ts) so the frontend needs zero changes once
 * NEXT_PUBLIC_API_URL is set.
 */
class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'orgName' => $this->org_name,
            'tagline' => $this->tagline,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'socialLinks' => $this->social_links ?? [],
            'navLinks' => $this->nav_links ?? [],
            'donateHref' => $this->donate_href,
        ];
    }
}
