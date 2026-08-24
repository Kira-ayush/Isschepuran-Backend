<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DonationMethodResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'fields' => $this->fields ?? [],
            'qrImage' => $this->getFirstMediaUrl('qr_image') ?: null,
            'qrImageAlt' => $this->qr_image_alt,
            'instructions' => $this->instructions,
            'order' => $this->order,
        ];
    }
}
