<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SectionHeading extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['key', 'eyebrow', 'heading'];

    public function registerMediaCollections(): void
    {
        // Optional — most sections are text-only. A few (e.g. Geographic
        // Reach's infographic map) benefit from one section-level image.
        $this->addMediaCollection('image')->singleFile()->useDisk('public');
    }

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
