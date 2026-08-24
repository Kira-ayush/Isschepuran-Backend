<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Replaces the implicit "lowest order = featured" convention with an
    // explicit admin choice. Only one row should ever have this true at a
    // time — enforced in GalleryItem::booted(), not at the DB level (a
    // partial unique index isn't portable/simple enough to be worth it
    // here for a single boolean flag on a 6-row table).
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('category_id');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
