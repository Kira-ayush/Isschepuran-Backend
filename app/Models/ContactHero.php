<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactHero extends Model
{
    protected $fillable = ['headline', 'subheading', 'background_image', 'text_alignment', 'show_glassmorphism_button'];

    /**
     * No source hero copy exists for this page in docs/raw-site-content.md
     * — this default is drafted copy, not migrated real content.
     */
    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], [
            'headline' => "We'd Love to Hear From You",
            'subheading' => 'Questions, ideas, or just want to say hello — reach out and our team will get back to you.',
        ]);
    }
}
