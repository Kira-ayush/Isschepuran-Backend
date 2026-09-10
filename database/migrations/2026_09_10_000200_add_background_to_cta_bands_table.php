<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Optional background photo (attaches via Spatie MediaLibrary's
    // 'background' collection on App\Models\CtaBand — no column) plus its
    // alt text, and a toggle for the darkening gradient overlay that keeps
    // the white CTA text readable over a photo. `overlay` defaults true and
    // only matters when a background image is set.
    public function up(): void
    {
        Schema::table('cta_bands', function (Blueprint $table) {
            $table->string('background_alt')->nullable()->after('subheading');
            $table->boolean('overlay')->default(true)->after('background_alt');
        });
    }

    public function down(): void
    {
        Schema::table('cta_bands', function (Blueprint $table) {
            $table->dropColumn(['background_alt', 'overlay']);
        });
    }
};
