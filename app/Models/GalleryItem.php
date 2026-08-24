<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class GalleryItem extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['caption', 'image_alt', 'category_id', 'order', 'is_published', 'is_featured'];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GalleryCategory::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }

    /**
     * Only one GalleryItem should ever be featured at a time — enforced
     * here rather than at the DB level (a partial unique index isn't
     * simple/portable enough to be worth it for one boolean flag). Marking
     * this item featured automatically un-features every other row.
     */
    protected static function booted(): void
    {
        static::saving(function (self $item) {
            if ($item->is_featured) {
                static::query()
                    ->when($item->exists, fn ($query) => $query->where('id', '!=', $item->id))
                    ->update(['is_featured' => false]);
            }
        });
    }
}
