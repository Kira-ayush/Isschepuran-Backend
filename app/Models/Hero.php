<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hero extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'eyebrow', 'headline', 'subheading',
        'primary_cta_label', 'primary_cta_href',
        'secondary_cta_label', 'secondary_cta_href',
        'background_alt',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background')->singleFile()->useDisk('public');
    }

    /**
     * Always fetch (and lazily create) the single hero row — there is
     * only ever one, edited from a Filament settings page, not a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'eyebrow' => 'Reforestation · Water · Education',
            'headline' => "Restoring Earth,\nEmpowering Communities",
            'subheading' => 'A philanthropic endeavor dedicated to reforestation, water conservation, and quality education for underserved children across eastern India.',
            'primary_cta_label' => 'Donate Now',
            'primary_cta_href' => '/get-involved#donate',
            'secondary_cta_label' => 'Our Mission',
            'secondary_cta_href' => '/about',
        ]);
    }
}
