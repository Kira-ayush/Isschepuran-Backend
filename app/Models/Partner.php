<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * One logo in the "Partners" section (frontend PartnersSection.tsx, shown
 * on Home + About). `group` picks which of the two labelled rows it lands
 * in. Not the same thing as CsrPartner — see the migration docblock.
 */
class Partner extends Model implements HasMedia
{
    use InteractsWithMedia;

    public const GROUPS = [
        'partnership' => 'In partnership with',
        'implemented_for' => 'Project implemented for',
    ];

    protected $fillable = ['name', 'group', 'logo_alt', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile()->useDisk('public');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
