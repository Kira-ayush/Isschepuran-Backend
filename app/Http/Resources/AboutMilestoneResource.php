<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutMilestoneResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'year' => $this->year,
            'title' => $this->title,
            'description' => $this->description,
            'order' => $this->order,
        ];
    }
}
