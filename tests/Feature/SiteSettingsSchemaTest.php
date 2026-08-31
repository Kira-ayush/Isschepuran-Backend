<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SiteSettingsSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_settings_schema_is_backfilled_when_logo_alt_column_is_missing(): void
    {
        Schema::dropIfExists('site_settings');

        Schema::create('site_settings', function ($table) {
            $table->id();
            $table->string('org_name');
            $table->text('tagline');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->json('social_links')->nullable();
            $table->json('nav_links')->nullable();
            $table->string('donate_href')->default('/get-involved#donate');
            $table->timestamps();
        });

        $settings = SiteSetting::current();

        $this->assertTrue(Schema::hasColumn('site_settings', 'logo_alt'));
        $this->assertSame('Ichhe Puran logo', $settings->logo_alt);

        $settings->update(['logo_alt' => 'Updated logo']);
        $this->assertSame('Updated logo', $settings->fresh()->logo_alt);
    }
}
