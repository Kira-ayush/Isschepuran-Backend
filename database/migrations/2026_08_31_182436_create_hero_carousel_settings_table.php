<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table (always exactly one row, id = 1) for carousel-wide
    // display options — indicator dot style and the gradient overlay toggle
    // are global to the whole carousel, not per-slide (see HeroSlide for
    // the per-slide content).
    public function up(): void
    {
        Schema::create('hero_carousel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('indicator_style')->default('dot'); // circle|dot|dash|plant — fixed set, enforced at the Filament Select level, not a DB enum
            $table->boolean('gradient_overlay')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_carousel_settings');
    }
};
