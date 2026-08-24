<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SdgAlignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'sdgNumber' => $this->sdg_number,
            'goalName' => $this->goal_name,
            'contributionText' => $this->contribution_text,
            'order' => $this->order,
        ];
    }
}
