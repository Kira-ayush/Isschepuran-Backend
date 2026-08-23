<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtaBand extends Model
{
    protected $table = 'cta_bands';

    protected $fillable = [
        'heading', 'subheading',
        'primary_cta_label', 'primary_cta_href',
        'secondary_cta_label', 'secondary_cta_href',
    ];

    /**
     * Always fetch (and lazily create) the single CTA band row — there is
     * only ever one, edited from a Filament settings page, not a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'heading' => 'Join the Movement for a Greener Planet',
            'subheading' => 'Your contribution directly funds the planting of saplings, the education of children, and the restoration of our precious ecosystems. Every wish matters.',
            'primary_cta_label' => 'Donate Now',
            'primary_cta_href' => '/get-involved#donate',
            'secondary_cta_label' => 'Volunteer',
            'secondary_cta_href' => '/get-involved#volunteer',
        ]);
    }
}
