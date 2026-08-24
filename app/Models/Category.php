<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'color', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function initiatives(): HasMany
    {
        return $this->hasMany(Initiative::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
