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

    public function createCampaign(Request $request)
    {
        $result = $this->campaignService->createCampaign($request);

        if (isset($result['error'])) {
            return response()->json($result, 400);
        }

        return response()->json($result, 201);
    }

    public function updateCampaign(Request $request)
    {
        // Log::info('Request received:', $request->all());

        // // Xác thực dữ liệu đầu vào
        // $validator = Validator::make($request->all(), [
        //     'id' => 'required|integer',
        //     'name' => 'sometimes|required|string|max:255',
        //     'province' => 'sometimes|required|string|max:255',
        //     'district' => 'sometimes|required|string|max:255',
        //     'location' => 'sometimes|required|string',
        //     'dateStart' => 'sometimes|required|date',
        //     'dateEnd' => 'sometimes|required|date',
        //     'totalMoney' => 'sometimes|required|numeric',
        //     'moneyByVNJN' => 'sometimes|required|numeric',
        //     'timeline' => 'sometimes|required|string',
        //     'infoContact' => 'sometimes|required|string',
        //     'infoOrganization' => 'sometimes|required|string',
        //     'image' => 'sometimes|file|mimes:jpeg,png,jpg,gif|max:51200',
        //     'description' => 'sometimes|required|string',
        //     'plan' => 'sometimes|required|string',
        // ]);

        // // Kiểm tra nếu dữ liệu không hợp lệ
        // if ($validator->fails()) {
        //     return response()->json([
        //         'error' => $validator->errors()
        //     ], 400);
        // }

        // Gọi CampaignService để cập nhật chiến dịch
        $result = $this->campaignService->updateCampaign($request);

        if ($result['status'] === 200) {
            return response()->json([
                'success' => 'Cập nhật chiến dịch thành công',
                'campaign' => $result['campaign']
            ], 200);
        } else {
            return response()->json(['error' => $result['error']], $result['status']);
        }
    }
    
}
