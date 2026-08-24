<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CsrPartnerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'logo' => $this->getFirstMediaUrl('logo') ?: null,
            'logoAlt' => $this->logo_alt,
            'order' => $this->order,
        ];
    }
}
