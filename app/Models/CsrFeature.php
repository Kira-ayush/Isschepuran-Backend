<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CsrFeature extends Model
{
    protected $fillable = ['title', 'description', 'icon', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
