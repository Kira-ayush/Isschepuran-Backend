<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactHeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'headline' => $this->headline,
            'subheading' => $this->subheading,
        ];
    }
}
