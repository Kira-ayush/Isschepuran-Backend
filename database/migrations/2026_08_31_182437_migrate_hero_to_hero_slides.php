<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Carries the one real singleton Hero row over into the new hero_slides
    // list table as slide #1, including its already-uploaded background
    // photo. Uses DB::table() query-builder calls only — never the Hero/
    // HeroSlide Eloquent models — since this must stay runnable even after
    // those classes are eventually deleted (see the plan's cleanup step).
    // A no-op on a fresh install that never had a `heroes` row.
    public function up(): void
    {
        if (! Schema::hasTable('heroes')) {
            return;
        }

        $hero = DB::table('heroes')->where('id', 1)->first();

        if (! $hero) {
            return;
        }

        $newId = DB::table('hero_slides')->insertGetId([
            'eyebrow' => $hero->eyebrow,
            'headline' => $hero->headline,
            'subheading' => $hero->subheading,
            'primary_cta_label' => $hero->primary_cta_label,
            'primary_cta_href' => $hero->primary_cta_href,
            'secondary_cta_label' => $hero->secondary_cta_label,
            'secondary_cta_href' => $hero->secondary_cta_href,
            'background_alt' => $hero->background_alt ?? null,
            'order' => 1,
            'is_published' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Critical step: repoint the Spatie media row so the already-
        // uploaded background photo isn't orphaned. Without this, the new
        // slide has all its scalar fields but no image.
        DB::table('media')
            ->where('model_type', 'App\\Models\\Hero')
            ->where('model_id', 1)
            ->where('collection_name', 'background')
            ->update([
                'model_type' => 'App\\Models\\HeroSlide',
                'model_id' => $newId,
            ]);
    }

    public function down(): void
    {
        // Best-effort only — does not attempt to resurrect the old heroes row.
        DB::table('hero_slides')->where('order', 1)->delete();
    }
};
