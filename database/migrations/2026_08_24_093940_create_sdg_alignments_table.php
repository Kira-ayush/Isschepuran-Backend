<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Renders on both Home and Impact pages — see SdgAlignmentResource
    // (Filament), which deliberately has no $navigationGroup for the same
    // reason SiteSetting/CtaBand don't.
    public function up(): void
    {
        Schema::create('sdg_alignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('sdg_number');
            $table->string('goal_name');
            $table->text('contribution_text');
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdg_alignments');
    }
};
