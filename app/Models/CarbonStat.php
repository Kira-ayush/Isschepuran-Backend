<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarbonStat extends Model
{
    protected $fillable = ['year', 'tons', 'is_projected', 'order', 'is_published'];

    protected $casts = [
        'is_projected' => 'boolean',
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
