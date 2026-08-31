<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroCarouselSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'indicatorStyle' => $this->indicator_style,
            'gradientOverlay' => (bool) $this->gradient_overlay,
        ];
    }
}
