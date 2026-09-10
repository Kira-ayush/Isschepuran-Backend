<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class CtaBand extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'cta_bands';

    protected $fillable = [
        'heading', 'subheading', 'background_alt', 'overlay', 'text_color',
        'primary_cta_label', 'primary_cta_href',
        'secondary_cta_label', 'secondary_cta_href',
    ];

    // Named presets offered in the admin alongside the "Custom hex" option.
    public const TEXT_COLOR_PRESETS = [
        'white' => ['label' => 'White (default)', 'hex' => null],
        'charcoal' => ['label' => 'Dark charcoal', 'hex' => '#24302a'],
        'forest' => ['label' => 'Forest green', 'hex' => '#1f5d42'],
        'mustard' => ['label' => 'Mustard', 'hex' => '#e9b949'],
    ];

    protected $casts = [
        'overlay' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        // ->useDisk('public') is mandatory (see CLAUDE.md "Hard rule … media uploads").
        $this->addMediaCollection('background')->singleFile()->useDisk('public');
    }

    /**
     * Always fetch (and lazily create) the single CTA band row — there is
     * only ever one, edited from a Filament settings page, not a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'heading' => 'Join the Movement for a Greener Planet',
            'subheading' => 'Your contribution directly funds the planting of saplings, the education of children, and the restoration of our precious ecosystems. Every wish matters.',
            'overlay' => true,
            'primary_cta_label' => 'Donate Now',
            'primary_cta_href' => '/get-involved#donate',
            'secondary_cta_label' => 'Volunteer',
            'secondary_cta_href' => '/get-involved#volunteer',
        ]);
    }
}
