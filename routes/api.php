<?php

use App\Http\Controllers\Api\AboutHeroController;
use App\Http\Controllers\Api\AboutIntroController;
use App\Http\Controllers\Api\AboutMilestoneController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CtaBandController;
use App\Http\Controllers\Api\GeographicReachController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\ImpactStatController;
use App\Http\Controllers\Api\InitiativeController;
use App\Http\Controllers\Api\InitiativesHeroController;
use App\Http\Controllers\Api\PillarController;
use App\Http\Controllers\Api\SectionHeadingController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\TeamMemberController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\TrustBadgeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public, read-only CMS content endpoints — versioned under /v1 so the
// frontend contract can evolve without breaking older deployed builds.
// Section-wise rather than one combined /home endpoint, so each section
// is independently reusable across pages and cacheable on its own.
Route::prefix('v1')->group(function () {
    Route::get('/settings', [SettingsController::class, 'show']);
    Route::get('/hero', [HeroController::class, 'show']);
    Route::get('/impact-stats', [ImpactStatController::class, 'index']);
    Route::get('/pillars', [PillarController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::get('/cta-band', [CtaBandController::class, 'show']);
    Route::get('/section-headings/{key}', [SectionHeadingController::class, 'show']);

    // About page
    Route::get('/about-hero', [AboutHeroController::class, 'show']);
    Route::get('/about-intro', [AboutIntroController::class, 'show']);
    Route::get('/geographic-reach', [GeographicReachController::class, 'index']);
    Route::get('/about-milestones', [AboutMilestoneController::class, 'index']);
    Route::get('/team', [TeamMemberController::class, 'index']);
    Route::get('/trust-badges', [TrustBadgeController::class, 'index']);

    // Initiatives page
    Route::get('/initiatives-hero', [InitiativesHeroController::class, 'show']);
    Route::get('/initiatives', [InitiativeController::class, 'index']);
    Route::get('/initiatives/{slug}', [InitiativeController::class, 'show']);
    Route::get('/categories', [CategoryController::class, 'index']);
});
