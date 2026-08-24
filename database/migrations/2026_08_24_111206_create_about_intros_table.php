<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table — origin story + vision/mission, the main narrative
    // block of the About page.
    public function up(): void
    {
        Schema::create('about_intros', function (Blueprint $table) {
            $table->id();
            $table->string('origin_title');
            $table->text('origin_body');
            $table->unsignedSmallInteger('established_year');
            $table->text('vision');
            $table->text('mission');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('about_intros');
    }
};
