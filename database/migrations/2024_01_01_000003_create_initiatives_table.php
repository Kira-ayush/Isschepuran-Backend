<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Powers both the Home page "Core Pillars" strip and the full
    // Initiatives page (filtered/grouped by category there).
    public function up(): void
    {
        Schema::create('initiatives', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('category', ['environment', 'water', 'community']);
            $table->string('slug')->unique();
            $table->text('summary');
            $table->longText('body')->nullable();
            $table->string('icon')->default('Sparkles');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->boolean('featured_on_home')->default(false);
            $table->timestamps();
            // Images attach via Spatie MediaLibrary (see Initiative model),
            // not a plain string column — gives real thumbnails/conversions.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiatives');
    }
};
