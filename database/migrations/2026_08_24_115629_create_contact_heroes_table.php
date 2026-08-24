<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton, same minimal shape as impact_heroes/gallery_heroes. No
    // source hero copy exists for this page — seeded default is drafted,
    // flagged in ContactPageSeeder's docblock.
    public function up(): void
    {
        Schema::create('contact_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_heroes');
    }
};
