<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        if (Schema::hasColumn('site_settings', 'logo_alt')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('logo_alt')->nullable()->after('org_name');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('site_settings') || ! Schema::hasColumn('site_settings', 'logo_alt')) {
            return;
        }

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('logo_alt');
        });
    }
};
