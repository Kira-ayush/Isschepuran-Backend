<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * Singleton (id = 1) backing the optional video hero band above the Home
 * page carousel. Edited from the ManageHomeVideoHero settings page, not a
 * list — same pattern as AboutHero / HeroCarouselSetting.
 */
class HomeVideoHero extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'is_enabled',
        'eyebrow',
        'headline',
        'subheading',
        'video_url',
        'poster_alt',
        'overlay',
        'cta1_label', 'cta1_href',
        'cta2_label', 'cta2_href',
        'cta3_label', 'cta3_href',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'overlay' => 'boolean',
    ];

    /**
     * Always fetch (and lazily create) the single row. Seeded with drafted
     * copy by HomePageSeeder; ships with is_enabled = false so the section
     * stays hidden until an admin uploads a video and turns it on.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'is_enabled' => false,
            'eyebrow' => 'Our Mission',
            'headline' => 'Planting Joy, Nurturing Hope, Growing Futures',
            'subheading' => 'Empowering communities and restoring nature, one initiative at a time.',
            'overlay' => true,
        ]);
    }

    public function registerMediaCollections(): void
    {
        // ->useDisk('public') is mandatory here (see backend/CLAUDE.md
        // "Hard rule … media uploads") — without it uploads land on the
        // private local disk and the URL is dead.
        $this->addMediaCollection('video')
            ->singleFile()
            ->acceptsMimeTypes(['video/mp4', 'video/webm'])
            ->useDisk('public');

        $this->addMediaCollection('poster')
            ->singleFile()
            ->useDisk('public');
    }
}
