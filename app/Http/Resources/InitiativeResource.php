<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InitiativeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'category' => $this->category,
            'summary' => $this->summary,
            'image' => $this->getFirstMediaUrl('image') ?: null,
            'icon' => $this->icon,
            'order' => $this->order,
        ];
    }
}
