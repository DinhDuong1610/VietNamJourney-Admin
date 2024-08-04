<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private CampaignService $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function getCampaignsIng($province)
    {
        return $this->campaignService->getCampaignsIng($province);
    }

    public function getCampaignsWill($province)
    {
        return $this->campaignService->getCampaignsWill($province);
    }

    public function getCampaignsEd($province)
    {
        return $this->campaignService->getCampaignsEd($province);
    }

    public function getCampaignDetail($id)
    {
        return $this->campaignService->getCampaignDetail_API($id);
    }

    public function getCampaignStatistics($province)
    {
        return $this->campaignService->getCampaignStatistics_API($province);
    }

    public function managerCampaign($userId)
    {
        try {
            $response = $this->campaignService->getManagerCampaigns($userId);
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể lấy thông tin chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStatusVolunteer(Request $request)
    {
        $userId = $request->input('userId');
        $campaignId = $request->input('campaignId');

        if (!$userId || !$campaignId) {
            return response()->json(["error" => "Thiếu tham số bắt buộc userId hoặc campaignId"], 400);
        }

        $status = $this->campaignService->getVolunteerStatus($userId, $campaignId);

        if ($status !== null) {
            return response()->json(["status" => $status], 200);
        } else {
            return response()->json(["error" => "Không tìm thấy trạng thái cho userId và campaignId đã cung cấp"], 404);
        }
    }
    
}
