<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Step 3 of converting Initiative.category from enum to Category FK —
    // only ever run this after CategoryMigrationSeeder has backfilled
    // category_id on every existing row. The original `nullOnDelete()`
    // foreign key can't coexist with a NOT NULL column (MySQL rejects it —
    // a SET NULL action requires nullability), so this drops and re-adds
    // the constraint as restrictOnDelete() (prevents deleting a Category
    // that's still in use, the right behavior for a master/reference
    // table) before making category_id required. Also drops the old
    // `category` enum column — on the production DB this was already gone
    // (a side effect of an earlier failed migration attempt, non-transactional
    // DDL under MySQL), but a fresh migrate-from-scratch (new dev machine,
    // test suite) needs this step to not fail inserts against a stray
    // NOT NULL `category` column.
    public function up(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable(false)->change();
            $table->foreign('category_id')->references('id')->on('categories')->restrictOnDelete();
        });

        if (Schema::hasColumn('initiatives', 'category')) {
            Schema::table('initiatives', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    public function down(): void
    {
        Schema::table('initiatives', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->enum('category', ['environment', 'water', 'community'])->after('title');
            $table->foreignId('category_id')->nullable()->change();
            $table->foreign('category_id')->references('id')->on('categories')->nullOnDelete();
        });
    }
};
