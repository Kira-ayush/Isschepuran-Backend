<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GeographicReachResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'state' => $this->state,
            'region' => $this->region,
            'description' => $this->description,
            'order' => $this->order,
        ];
    }
}
