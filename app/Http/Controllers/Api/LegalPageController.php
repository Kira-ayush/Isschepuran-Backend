<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LegalPageResource;
use App\Http\Resources\LegalPageSummaryResource;
use App\Models\LegalPage;
use Illuminate\Http\Response;

class LegalPageController extends Controller
{
    // GET /api/v1/legal-pages — powers the Footer's link list.
    public function index()
    {
        return LegalPageSummaryResource::collection(LegalPage::published()->get());
    }

    // GET /api/v1/legal-pages/{slug} — powers /legal/{slug}.
    public function show(string $slug)
    {
        $page = LegalPage::published()->where('slug', $slug)->first();

        if (! $page) {
            return response()->json(['message' => 'Not found'], Response::HTTP_NOT_FOUND);
        }

        return new LegalPageResource($page);
    }
}
