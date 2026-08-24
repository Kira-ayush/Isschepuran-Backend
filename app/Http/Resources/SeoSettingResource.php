<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Shape matches the Next.js `SeoSetting` type exactly (frontend/src/lib/types.ts).
 * Every field is nullable/optional by design — the frontend falls back to
 * this page's normal content-derived title/description/image whenever an
 * override hasn't been set in the admin panel.
 */
class SeoSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'metaTitle' => $this->meta_title,
            'metaDescription' => $this->meta_description,
            'ogTitle' => $this->og_title,
            'ogDescription' => $this->og_description,
            'ogImage' => $this->getFirstMediaUrl('og_image') ?: null,
            'twitterTitle' => $this->twitter_title,
            'twitterDescription' => $this->twitter_description,
            'twitterImage' => $this->getFirstMediaUrl('twitter_image') ?: null,
        ];
    }
}
