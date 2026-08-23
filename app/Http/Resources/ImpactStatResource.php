<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ImpactStatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'label' => $this->label,
            'value' => $this->value,
            'suffix' => $this->suffix,
            'icon' => $this->icon,
            'order' => $this->order,
        ];
    }
}
