<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InitiativesHero extends Model
{
    protected $fillable = ['headline', 'subheading', 'background_image', 'text_alignment', 'show_glassmorphism_button'];

    /**
     * Always fetch (and lazily create) the single Initiatives hero row.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => 'Nurturing Nature, Empowering Lives',
            'subheading' => 'At Ichhe Puran, we translate philanthropic vision into tangible environmental and social impact through carefully structured initiatives.',
        ]);
    }
}
