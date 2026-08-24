<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DonationMethod extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['type', 'title', 'fields', 'instructions', 'qr_image_alt', 'order', 'is_published'];

    protected $casts = [
        'fields' => 'array',
        'is_published' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('qr_image')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
