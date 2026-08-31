<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Final cleanup step of the Hero -> HeroSlide carousel conversion — run
    // only after migrate_hero_to_hero_slides has confirmed the real row's
    // data and media were carried over successfully.
    public function up(): void
    {
        Schema::dropIfExists('heroes');
    }

    // Reversible for completeness, not because anything should ever read
    // from this table again — mirrors 2026_08_23_200132_create_heroes_table
    // + the background_alt column added by 2026_08_24_140414_add_alt_text_to_media_fields.
    public function down(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow');
            $table->string('headline');
            $table->text('subheading');
            $table->string('primary_cta_label');
            $table->string('primary_cta_href');
            $table->string('secondary_cta_label');
            $table->string('secondary_cta_href');
            $table->string('background_alt')->nullable();
            $table->timestamps();
        });
    }
};
