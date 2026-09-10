<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Logo-wall entries for the "Partners" section shown on the Home and
    // About pages (frontend PartnersSection.tsx). `group` splits them into
    // the two labelled rows — 'partnership' ("In partnership with") and
    // 'implemented_for' ("Project implemented for"). Fixed 2-value set,
    // enforced at the Filament Select level, not a DB enum — same
    // enum-vs-FK call as HeroCarouselSetting.indicator_style.
    //
    // Distinct from CsrPartner (the "Our CSR Partners" row inside the
    // Impact page's Corporate Social Synergy section) — different framing,
    // different pages.
    //
    // The logo image attaches via Spatie MediaLibrary ('logo' collection
    // on App\Models\Partner), not a string column.
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('group')->default('partnership'); // partnership | implemented_for
            $table->string('logo_alt')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};
