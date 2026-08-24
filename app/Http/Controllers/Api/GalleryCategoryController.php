<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\GalleryCategory;

class GalleryCategoryController extends Controller
{
    // GET /api/v1/gallery-categories
    // The full category master, in order — lets the frontend build filter
    // tabs dynamically (and hide categories with zero published items)
    // instead of a hardcoded category list. Reuses the generic
    // CategoryResource shape ({slug, name, color, order}) — same as
    // Initiative's category, just a different underlying master table.
    public function index()
    {
        return CategoryResource::collection(
            GalleryCategory::published()->get()
        );
    }
}
