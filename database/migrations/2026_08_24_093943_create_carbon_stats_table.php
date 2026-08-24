<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carbon_stats', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->unsignedInteger('tons');
            $table->boolean('is_projected')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carbon_stats');
    }
};
