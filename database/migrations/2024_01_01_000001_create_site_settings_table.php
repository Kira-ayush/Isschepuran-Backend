<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — always exactly one row (id = 1), edited from a
    // dedicated Filament settings page rather than a list of records.
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('org_name');
            $table->text('tagline');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->json('social_links')->nullable(); // [{label, href}]
            $table->json('nav_links')->nullable();     // [{label, href}]
            $table->string('donate_href')->default('/get-involved#donate');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
