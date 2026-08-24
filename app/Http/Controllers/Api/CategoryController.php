<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    // GET /api/v1/categories
    // The full category master, in order — lets the frontend group/label
    // Initiatives dynamically instead of a hardcoded category list.
    public function index()
    {
        return CategoryResource::collection(
            Category::published()->get()
        );
    }
}
