<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class HeroSlide extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'eyebrow', 'headline', 'subheading',
        'primary_cta_label', 'primary_cta_href',
        'secondary_cta_label', 'secondary_cta_href',
        'background_alt', 'order', 'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('background')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
