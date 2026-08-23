<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionHeading extends Model
{
    protected $fillable = ['key', 'eyebrow', 'heading'];

    /**
     * Fetch (and lazily create with the given defaults) the row for a given
     * section key — e.g. 'pillars', 'testimonials'. One row per key, not a
     * true global singleton like Hero/SiteSetting.
     */
    public static function forKey(string $key, string $defaultEyebrow, string $defaultHeading): self
    {
        return static::firstOrCreate(
            ['key' => $key],
            ['eyebrow' => $defaultEyebrow, 'heading' => $defaultHeading],
        );
    }
}
