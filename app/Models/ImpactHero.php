<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImpactHero extends Model
{
    protected $fillable = ['headline', 'subheading'];

    /**
     * Always fetch (and lazily create) the single Impact hero row.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => 'Measuring What Matters',
            'subheading' => 'From verified SDG contributions to transparent carbon reporting, every number on this page traces back to a real project on the ground.',
        ]);
    }
}
