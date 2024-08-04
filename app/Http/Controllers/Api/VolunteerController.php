<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\VolunteerService;

class VolunteerController extends Controller
{
    private VolunteerService $volunteerService;

    public function __construct(VolunteerService $volunteerService)
    {
        $this->volunteerService = $volunteerService;
    }

    public function getJoined(Request $request, $campaignId)
    {
        $volunteers = $this->volunteerService->getJoinedVolunteers($campaignId);

        return response()->json(['volunteers' => $volunteers]);
    }

    public function getPending(Request $request, $campaignId)
    {
        $volunteers = $this->volunteerService->getPendingVolunteers($campaignId);

        return response()->json(['volunteers' => $volunteers]);
    }

}
