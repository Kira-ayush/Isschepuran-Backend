<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Testimonial extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['quote', 'name', 'role', 'photo_alt', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('photo')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
