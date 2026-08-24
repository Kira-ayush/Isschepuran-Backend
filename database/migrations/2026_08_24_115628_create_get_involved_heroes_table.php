<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton, same minimal shape as impact_heroes/gallery_heroes.
    public function up(): void
    {
        Schema::create('get_involved_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('get_involved_heroes');
    }
};
