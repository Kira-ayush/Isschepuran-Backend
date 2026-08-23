<?php

use App\Http\Controllers\Api\CtaBandController;
use App\Http\Controllers\Api\HeroController;
use App\Http\Controllers\Api\ImpactStatController;
use App\Http\Controllers\Api\PillarController;
use App\Http\Controllers\Api\SectionHeadingController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\TestimonialController;
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
});
