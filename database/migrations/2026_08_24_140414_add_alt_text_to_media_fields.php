<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Alt text for every single-file image collection across the CMS —
    // none had one until now, meaning screen readers and search engines got
    // nothing meaningful for any uploaded image. One column per image field,
    // nullable (falls back to a sensible default at the Resource layer, see
    // each Http Resource's getAlt()-style fallback).
    public function up(): void
    {
        Schema::table('heroes', function (Blueprint $table) {
            $table->string('background_alt')->nullable()->after('secondary_cta_href');
        });

        Schema::table('about_intros', function (Blueprint $table) {
            $table->string('origin_image_alt')->nullable()->after('mission');
        });

        Schema::table('about_milestones', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('description');
        });

        Schema::table('initiatives', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('body');
        });

        Schema::table('section_headings', function (Blueprint $table) {
            $table->string('image_alt')->nullable()->after('heading');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->string('photo_alt')->nullable()->after('bio');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('photo_alt')->nullable()->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('heroes', fn (Blueprint $table) => $table->dropColumn('background_alt'));
        Schema::table('about_intros', fn (Blueprint $table) => $table->dropColumn('origin_image_alt'));
        Schema::table('about_milestones', fn (Blueprint $table) => $table->dropColumn('image_alt'));
        Schema::table('initiatives', fn (Blueprint $table) => $table->dropColumn('image_alt'));
        Schema::table('section_headings', fn (Blueprint $table) => $table->dropColumn('image_alt'));
        Schema::table('team_members', fn (Blueprint $table) => $table->dropColumn('photo_alt'));
        Schema::table('testimonials', fn (Blueprint $table) => $table->dropColumn('photo_alt'));
    }
};
