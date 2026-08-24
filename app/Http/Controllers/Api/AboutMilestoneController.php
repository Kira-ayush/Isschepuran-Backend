<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutMilestoneResource;
use App\Models\AboutMilestone;

class AboutMilestoneController extends Controller
{
    // GET /api/v1/about-milestones
    public function index()
    {
        return AboutMilestoneResource::collection(
            AboutMilestone::published()->get()
        );
    }
}
