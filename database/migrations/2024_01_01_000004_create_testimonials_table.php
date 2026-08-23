<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->text('quote');
            $table->string('name');
            $table->string('role');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            // Photo attaches via Spatie MediaLibrary — optional, per the
            // project doc's caution against AI-generated headshots for
            // named real people (see Filament resource help text).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
