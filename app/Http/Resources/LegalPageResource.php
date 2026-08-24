<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LegalPageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->slug,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'updatedAt' => $this->updated_at?->toDateString(),
        ];
    }
}
