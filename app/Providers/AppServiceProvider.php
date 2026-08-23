<?php

namespace App\Providers;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // The frontend's lib/api.ts expects flat resource shapes matching
        // frontend/src/lib/types.ts exactly, not Laravel's default {"data": ...} envelope.
        JsonResource::withoutWrapping();
    }
}
