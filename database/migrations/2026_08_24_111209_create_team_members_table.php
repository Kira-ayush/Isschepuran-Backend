<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('bio')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            // Photo attaches via Spatie MediaLibrary — optional, real photos
            // only (see TeamMemberResource's helper text, same rule as
            // Testimonial's photo field).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
