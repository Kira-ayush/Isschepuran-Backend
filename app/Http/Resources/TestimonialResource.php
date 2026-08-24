<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'quote' => $this->quote,
            'name' => $this->name,
            'role' => $this->role,
            'photo' => $this->getFirstMediaUrl('photo') ?: null,
            'photoAlt' => $this->photo_alt,
            'order' => $this->order,
        ];
    }
}
