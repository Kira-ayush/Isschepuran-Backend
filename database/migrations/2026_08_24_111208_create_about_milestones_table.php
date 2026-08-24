<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // "Journey of Impact" timeline for the About page specifically. Kept
    // separate from any future Impact-page timeline — the two versions in
    // the original site contradict each other (see docs/raw-site-content.md),
    // they are not the same list duplicated.
    public function up(): void
    {
        Schema::create('about_milestones', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('title');
            $table->text('description');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_milestones');
    }
};
