<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Step 1 of converting GalleryItem.category from a fixed string to a
    // GalleryCategory master (FK) — mirrors Initiative's enum→FK sequence
    // (see backend/CLAUDE.md). Nullable for now — GalleryPageSeeder
    // backfills it from the old `category` string column, then a later
    // migration drops that column and makes this NOT NULL.
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')
                ->constrained('gallery_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
        });
    }
};
