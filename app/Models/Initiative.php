<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Initiative extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title', 'category', 'slug', 'summary', 'body',
        'icon', 'order', 'is_published', 'featured_on_home',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'featured_on_home' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }

    public function scopeFeaturedOnHome($query)
    {
        return $query->published()->where('featured_on_home', true);
    }
}
