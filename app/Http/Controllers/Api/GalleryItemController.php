<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GalleryItemResource;
use App\Models\GalleryItem;

class GalleryItemController extends Controller
{
    // GET /api/v1/gallery-items
    public function index()
    {
        return GalleryItemResource::collection(
            GalleryItem::with('category')->published()->get()
        );
    }
}
