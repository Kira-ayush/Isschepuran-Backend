<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\RateLimiter;
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

        // Named limiter for the public write endpoints (Volunteer/CSR/
        // Newsletter/Contact/donations). Deliberately NOT the plain
        // `throttle:5,1` shorthand — that keys only by IP+domain
        // (Illuminate\Routing\Middleware\ThrottleRequests::resolveRequestSignature()),
        // so every route sharing that middleware would draw from one
        // pooled bucket per IP — a burst against one form (e.g. newsletter
        // spam) would lock out a legitimate user submitting a completely
        // different form seconds later. Keying by IP + request path gives
        // each endpoint its own independent 5/minute bucket instead.
        RateLimiter::for('public-forms', function ($request) {
            return Limit::perMinute(5)->by($request->ip() . '|' . $request->path());
        });
    }
}
