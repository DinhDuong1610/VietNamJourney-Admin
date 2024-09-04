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

    public function register(Request $request)
    {
        try {
            $result = $this->volunteerService->registerVolunteer($request->all());
            return response()->json(['message' => $result['message']], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        // Validate input
        $request->validate([
            'campaignId' => 'required|integer',
            'userId' => 'required|integer',
            'status' => 'required|integer',
        ]);

        try {
            $affected = $this->volunteerService->updateVolunteerStatus(
                $request->input('campaignId'),
                $request->input('userId'),
                $request->input('status')
            );

            if ($affected) {
                return response()->json(['message' => 'Status updated successfully'], 200);
            } else {
                return response()->json(['message' => 'No records updated'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
