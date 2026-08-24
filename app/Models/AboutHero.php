<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutHero extends Model
{
    protected $fillable = ['headline', 'subheading'];

    /**
     * Always fetch (and lazily create) the single About hero row.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => 'Our Roots in Resilience',
            'subheading' => 'Born from the tides of necessity, Ichhe Puran stands as a beacon of sustainable hope for the coastal communities of India.',
        ]);
    }
}
