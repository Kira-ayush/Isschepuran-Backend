<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HeroCarouselSetting extends Model
{
    protected $fillable = ['indicator_style', 'gradient_overlay'];

    protected $casts = [
        'gradient_overlay' => 'boolean',
    ];

    /**
     * Always fetch (and lazily create) the single settings row — there is
     * only ever one, edited from a Filament settings page, not a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'indicator_style' => 'dot',
            'gradient_overlay' => true,
        ]);
    }
}
