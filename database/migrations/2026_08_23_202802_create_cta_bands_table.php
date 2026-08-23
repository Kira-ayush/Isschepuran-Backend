<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — always exactly one row (id = 1), edited from a
    // dedicated Filament settings page, same pattern as Hero/SiteSetting.
    // Powers the Home page's closing "Join the Movement" band, which was
    // previously hardcoded directly into CtaBand.tsx.
    public function up(): void
    {
        Schema::create('cta_bands', function (Blueprint $table) {
            $table->id();
            $table->string('heading');
            $table->text('subheading');
            $table->string('primary_cta_label');
            $table->string('primary_cta_href');
            $table->string('secondary_cta_label');
            $table->string('secondary_cta_href');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cta_bands');
    }
};
