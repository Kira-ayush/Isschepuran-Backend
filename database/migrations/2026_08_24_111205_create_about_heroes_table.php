<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — always exactly one row (id = 1). About page's hero
    // has no CTA buttons (unlike Home's Hero), matching the actual source
    // content — just a headline + subheading banner.
    public function up(): void
    {
        Schema::create('about_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_heroes');
    }
};
