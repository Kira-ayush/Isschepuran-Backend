<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Singleton table (always exactly one row, id = 1) for the optional
    // full-bleed video hero band that renders ABOVE the image carousel on
    // the Home page (see HeroSlide / HeroCarouselSetting for the carousel
    // itself). The video attaches via Spatie MediaLibrary ('video'
    // collection on App\Models\HomeVideoHero), with video_url as an
    // external fallback; poster attaches via the 'poster' collection.
    // is_enabled is the master toggle — the whole section only renders when
    // it's true, so this ships disabled and changes nothing until an admin
    // uploads a video and turns it on.
    public function up(): void
    {
        Schema::create('home_video_heroes', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->string('eyebrow')->nullable();
            $table->string('headline')->nullable();
            $table->text('subheading')->nullable();
            $table->string('video_url')->nullable(); // used only when no file is uploaded to the 'video' collection
            $table->string('poster_alt')->nullable();
            $table->boolean('overlay')->default(true); // dark gradient over the video so overlay text stays readable
            $table->string('cta1_label')->nullable();
            $table->string('cta1_href')->nullable();
            $table->string('cta2_label')->nullable();
            $table->string('cta2_href')->nullable();
            $table->string('cta3_label')->nullable();
            $table->string('cta3_href')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_video_heroes');
    }
};
