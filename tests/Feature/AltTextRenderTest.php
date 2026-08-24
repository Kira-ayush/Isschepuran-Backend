<?php

namespace Tests\Feature;

use App\Models\AboutMilestone;
use App\Models\Category;
use App\Models\Initiative;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Throwaway render-test for the alt-text form fields added across every
 * Filament page/resource that has an image upload. Filament class/type
 * errors in form()/table() bodies only surface at render time.
 */
class AltTextRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function actingUser(): User
    {
        // Filament's Authenticate middleware only allows panel access to a
        // User model that doesn't implement FilamentUser when app.env is
        // 'local' — phpunit.xml sets APP_ENV=testing, so this needs to be
        // overridden per-test rather than reflecting a real access gap.
        config(['app.env' => 'local']);

        $user = User::factory()->create();
        $this->actingAs($user);

        return $user;
    }

    public function test_manage_hero_renders(): void
    {
        $this->actingUser();
        $this->get('/admin/hero')->assertOk();
    }

    public function test_manage_about_intro_renders(): void
    {
        $this->actingUser();
        $this->get('/admin/about-intro')->assertOk();
    }

    public function test_about_milestone_pages_render(): void
    {
        $this->actingUser();
        $milestone = AboutMilestone::create([
            'year' => '2021', 'title' => 'Test', 'description' => 'desc', 'order' => 1,
        ]);
        $this->get('/admin/about-milestones')->assertOk();
        $this->get('/admin/about-milestones/create')->assertOk();
        $this->get("/admin/about-milestones/{$milestone->id}/edit")->assertOk();
    }

    public function test_initiative_pages_render(): void
    {
        $this->actingUser();
        $category = Category::create(['name' => 'Environment', 'slug' => 'environment', 'color' => 'forest', 'order' => 1]);
        $initiative = Initiative::create([
            'title' => 'Test', 'category_id' => $category->id, 'slug' => 'test',
            'summary' => 'summary', 'order' => 1,
        ]);
        $this->get('/admin/initiatives')->assertOk();
        $this->get('/admin/initiatives/create')->assertOk();
        $this->get("/admin/initiatives/{$initiative->id}/edit")->assertOk();
    }

    public function test_team_member_pages_render(): void
    {
        $this->actingUser();
        $member = TeamMember::create(['name' => 'Test', 'role' => 'Role', 'order' => 1]);
        $this->get('/admin/team-members')->assertOk();
        $this->get('/admin/team-members/create')->assertOk();
        $this->get("/admin/team-members/{$member->id}/edit")->assertOk();
    }

    public function test_testimonial_pages_render(): void
    {
        $this->actingUser();
        $testimonial = Testimonial::create(['quote' => 'q', 'name' => 'Test', 'role' => 'Role', 'order' => 1]);
        $this->get('/admin/testimonials')->assertOk();
        $this->get('/admin/testimonials/create')->assertOk();
        $this->get("/admin/testimonials/{$testimonial->id}/edit")->assertOk();
    }
}
