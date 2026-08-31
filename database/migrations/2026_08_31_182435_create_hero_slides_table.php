<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Replaces the old singleton `heroes` table (see migrate_hero_to_hero_slides
    // and drop_heroes_table) — the Home hero is now a carousel of admin-managed
    // slides, same list-resource shape as Testimonial/AboutMilestone/etc.
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow');
            $table->string('headline');
            $table->text('subheading');
            $table->string('primary_cta_label');
            $table->string('primary_cta_href');
            $table->string('secondary_cta_label');
            $table->string('secondary_cta_href');
            $table->string('background_alt')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            // Background image attaches via Spatie MediaLibrary (see HeroSlide model).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
