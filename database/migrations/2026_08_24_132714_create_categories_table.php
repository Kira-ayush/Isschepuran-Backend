<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // The "category master" for Initiatives — lets admins add/remove/reorder
    // categories (currently Environment/Water/Community) without a code
    // change. `color` is one of a fixed set of approved design-token keys
    // (see CategoryResource's Select options), not a free color picker —
    // keeps new categories on-brand automatically.
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color')->default('forest');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
