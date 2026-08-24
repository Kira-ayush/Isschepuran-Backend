<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GalleryItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'image' => $this->getFirstMediaUrl('image') ?: null,
            'imageAlt' => $this->image_alt,
            'caption' => $this->caption,
            'category' => new CategoryResource($this->category),
            'order' => $this->order,
            'isFeatured' => (bool) $this->is_featured,
        ];
    }
}
