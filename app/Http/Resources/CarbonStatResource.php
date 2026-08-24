<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CarbonStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'year' => $this->year,
            'tons' => $this->tons,
            'isProjected' => (bool) $this->is_projected,
            'order' => $this->order,
        ];
    }
}
