<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SdgAlignment extends Model
{
    protected $fillable = ['sdg_number', 'goal_name', 'contribution_text', 'order', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->orderBy('order');
    }
}
