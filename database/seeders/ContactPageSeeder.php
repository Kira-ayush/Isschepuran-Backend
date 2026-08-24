<?php

namespace Database\Seeders;

use App\Models\ContactHero;
use Illuminate\Database\Seeder;

/**
 * Seeds the Contact page. No source hero copy exists for this page in
 * docs/raw-site-content.md — the headline/subheading below are drafted,
 * not migrated, same flagging convention as ImpactHero/GalleryHero.
 * Office info (phone/email/address) already lives on SiteSetting and is
 * reused as-is — no separate content type needed for it.
 *
 * Run with: php artisan db:seed --class=ContactPageSeeder
 */
class ContactPageSeeder extends Seeder
{
    public function run(): void
    {
        ContactHero::current()->update([
            'headline' => "We'd Love to Hear From You",
            'subheading' => 'Questions, ideas, or just want to say hello — reach out and our team will get back to you.',
        ]);
    }
}
