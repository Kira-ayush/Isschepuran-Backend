<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TeamMemberResource;
use App\Models\TeamMember;

class TeamMemberController extends Controller
{
    // GET /api/v1/team
    public function index()
    {
        return TeamMemberResource::collection(
            TeamMember::published()->get()
        );
    }
}
