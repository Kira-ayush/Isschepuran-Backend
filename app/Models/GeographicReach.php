<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeographicReach extends Model
{
    protected $fillable = ['state', 'region', 'description', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
