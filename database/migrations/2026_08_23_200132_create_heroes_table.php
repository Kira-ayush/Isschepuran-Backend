<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — always exactly one row (id = 1), edited from a
    // dedicated Filament settings page, same pattern as SiteSetting.
    public function up(): void
    {
        Schema::create('heroes', function (Blueprint $table) {
            $table->id();
            $table->string('eyebrow');
            $table->string('headline');
            $table->text('subheading');
            $table->string('primary_cta_label');
            $table->string('primary_cta_href');
            $table->string('secondary_cta_label');
            $table->string('secondary_cta_href');
            $table->timestamps();
            // Background image attaches via Spatie MediaLibrary (see Hero model).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('heroes');
    }
};
