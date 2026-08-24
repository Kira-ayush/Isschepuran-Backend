<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Step 1 of converting Initiative.category from a fixed enum to a
    // Category master (FK). Nullable for now — a seeder backfills it from
    // the old `category` enum column, then a later migration drops that
    // column and makes this NOT NULL. See CategoryMigrationSeeder.
    public function up(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
