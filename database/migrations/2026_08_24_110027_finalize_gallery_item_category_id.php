<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Step 2: drop and re-add the FK as restrictOnDelete() (a
    // nullOnDelete() FK can't coexist with NOT NULL — MySQL rejects it),
    // then make category_id required, then drop the old `category` string
    // column outright. Unlike the first time this pattern was used
    // (Initiative.category — see backend/CLAUDE.md's migration-sequence
    // note), the column drop is included here from the start rather than
    // relying on a partially-failed first attempt to have removed it.
    public function up(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('gallery_categories')->restrictOnDelete();
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }

    public function down(): void
    {
        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('category')->default('general')->after('image_alt');
            $table->foreignId('category_id')->nullable()->change();
            $table->foreign('category_id')->references('id')->on('gallery_categories')->nullOnDelete();
        });
    }
};
