<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryHero extends Model
{
    protected $fillable = ['headline', 'subheading'];

    /**
     * Always fetch (and lazily create) the single Gallery hero row.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => 'Moments From the Field',
            'subheading' => 'A visual record of restoration projects, community events, and everyday work across our operating villages — before, during, and after.',
        ]);
    }
}
