<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — always exactly one row (id = 1). Like About's hero,
    // no CTA buttons — matches the actual source content.
    public function up(): void
    {
        Schema::create('initiatives_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('headline');
            $table->text('subheading');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('initiatives_heroes');
    }
};
