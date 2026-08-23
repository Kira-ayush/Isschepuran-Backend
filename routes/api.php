<?php

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\SettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public, read-only CMS content endpoints — versioned under /v1 so the
// frontend contract can evolve without breaking older deployed builds.
Route::prefix('v1')->group(function () {
    Route::get('/settings', [SettingsController::class, 'show']);
    Route::get('/home', [HomeController::class, 'show']);
});
