<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // `category` is a plain string + curated 3-option Filament Select, NOT
    // a Category-style master/FK — the CMS spec hardcodes exactly 3 fixed
    // values (before_after/event/general) with no growth signal, unlike
    // Initiative.category which the user explicitly said "may increase or
    // decrease." See GalleryItemResource's docblock for the same rationale.
    public function up(): void
    {
        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('image_alt')->nullable();
            $table->text('caption')->nullable();
            $table->string('category')->default('general');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            // Image attaches via Spatie MediaLibrary (see GalleryItem
            // model), not a plain string column.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_items');
    }
};
