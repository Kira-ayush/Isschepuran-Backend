<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_methods', function (Blueprint $table) {
            $table->string('qr_image_alt')->nullable()->after('instructions');
        });
    }

    public function down(): void
    {
        Schema::table('donation_methods', function (Blueprint $table) {
            $table->dropColumn('qr_image_alt');
        });
    }
};
