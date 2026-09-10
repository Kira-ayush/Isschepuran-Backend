<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Hex colour for the CTA band's heading + subheading text. Null means
    // "white" (the default, for the dark band / a dark background photo).
    // The admin picks a named preset or types a custom hex — either way a
    // hex string lands here.
    public function up(): void
    {
        Schema::table('cta_bands', function (Blueprint $table) {
            $table->string('text_color', 9)->nullable()->after('overlay');
        });
    }

    public function down(): void
    {
        Schema::table('cta_bands', function (Blueprint $table) {
            $table->dropColumn('text_color');
        });
    }
};
