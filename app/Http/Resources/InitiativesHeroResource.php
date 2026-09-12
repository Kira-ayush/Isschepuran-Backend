<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InitiativesHeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'headline' => $this->headline,
            'subheading' => $this->subheading,
            'backgroundImage' => $this->background_image ? url('storage/' . $this->background_image) : null,
            'textAlignment' => $this->text_alignment,
            'showGlassmorphismButton' => (bool) $this->show_glassmorphism_button,
        ];
    }
}
