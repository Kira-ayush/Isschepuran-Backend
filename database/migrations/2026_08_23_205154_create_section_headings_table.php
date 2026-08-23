<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Small reusable eyebrow+heading pairs for list-style sections (Pillars,
    // Testimonials, and future ones) that don't have their own singleton
    // settings page — edited via a widget on top of that section's own
    // Filament resource list, keyed by section identifier.
    public function up(): void
    {
        Schema::create('section_headings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('eyebrow');
            $table->string('heading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('section_headings');
    }
};
