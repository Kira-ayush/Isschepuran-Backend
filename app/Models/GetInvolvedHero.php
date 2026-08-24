<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GetInvolvedHero extends Model
{
    protected $fillable = ['headline', 'subheading'];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => 'Your Contribution Sows the Seeds of Change',
            'subheading' => 'Whether you choose to donate financially, volunteer your time, or partner with us through CSR, every effort helps us restore nature and empower communities.',
        ]);
    }
}
