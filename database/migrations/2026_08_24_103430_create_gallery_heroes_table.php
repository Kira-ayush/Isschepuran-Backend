<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton, same shape as impact_heroes/about_heroes/initiatives_heroes
    // — no image/CTA columns. No source hero copy exists for this page.
    public function up(): void
    {
        Schema::create('gallery_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_heroes');
    }
};
