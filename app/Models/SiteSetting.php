<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'org_name', 'tagline', 'phone', 'email', 'address',
        'social_links', 'nav_links', 'donate_href',
    ];

    protected $casts = [
        'social_links' => 'array',
        'nav_links' => 'array',
    ];

    /**
     * Always fetch (and lazily create) the single settings row — there is
     * only ever one, edited from a Filament settings page, not a list.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'org_name' => 'Ichhe Puran',
            'tagline' => 'Nurturing nature, restoring ecosystems, and empowering communities through transparent philanthropy.',
            'phone' => '',
            'email' => '',
            'address' => '',
            'nav_links' => [
                ['label' => 'Home', 'href' => '/'],
                ['label' => 'About', 'href' => '/about'],
                ['label' => 'Initiatives', 'href' => '/initiatives'],
                ['label' => 'Impact', 'href' => '/impact'],
                ['label' => 'Gallery', 'href' => '/gallery'],
                ['label' => 'Contact', 'href' => '/contact'],
            ],
        ]);
    }
}
