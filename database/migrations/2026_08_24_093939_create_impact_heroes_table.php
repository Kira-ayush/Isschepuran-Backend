<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton, same shape as about_heroes/initiatives_heroes — no image
    // or CTA fields, matching that established minimal-hero pattern.
    public function up(): void
    {
        Schema::create('impact_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impact_heroes');
    }
};
