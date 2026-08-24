<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SeoSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'key', 'meta_title', 'meta_description',
        'og_title', 'og_description',
        'twitter_title', 'twitter_description',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('og_image')->singleFile()->useDisk('public');
        $this->addMediaCollection('twitter_image')->singleFile()->useDisk('public');
    }

    /**
     * Fetch (and lazily create) the row for a given page key — e.g. 'home',
     * 'about', 'initiatives'. One row per key, same pattern as SectionHeading.
     */
    public static function forKey(string $key): self
    {
        return static::firstOrCreate(['key' => $key]);
    }
}
