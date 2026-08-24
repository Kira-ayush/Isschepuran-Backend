<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalleryCategory extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function galleryItems(): HasMany
    {
        // Explicit FK: without it, Eloquent infers "gallery_category_id"
        // from this model's class name, but the actual column (matching
        // Initiative.category_id's naming) is just "category_id".
        return $this->hasMany(GalleryItem::class, 'category_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
