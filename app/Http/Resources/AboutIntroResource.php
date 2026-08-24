<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutIntroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'originTitle' => $this->origin_title,
            'originBody' => $this->origin_body,
            'establishedYear' => $this->established_year,
            'vision' => $this->vision,
            'mission' => $this->mission,
            'originImage' => $this->getFirstMediaUrl('origin_image') ?: null,
        ];
    }
}
