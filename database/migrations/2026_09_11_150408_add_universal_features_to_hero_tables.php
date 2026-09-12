<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tables = [
        'about_heroes',
        'contact_heroes',
        'gallery_heroes',
        'get_involved_heroes',
        'impact_heroes',
        'initiatives_heroes'
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('background_image')->nullable();
                $table->string('text_alignment')->default('center');
                $table->boolean('show_glassmorphism_button')->default(true);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['background_image', 'text_alignment', 'show_glassmorphism_button']);
            });
        }
    }
};
