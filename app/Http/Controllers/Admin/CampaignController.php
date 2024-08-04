<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Http\Services\CampaignService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Admin\ModelNotFoundException;



class CampaignController extends Controller
{
    private CampaignService $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function list()
    {
        $campaigns = $this->campaignService->getAll();
        foreach ($campaigns as $campaign) {
            $campaign->status = $this->getCampaignStatus($campaign->dateStart, $campaign->dateEnd);
        }
        return view('admin.pages.campaign.index_list', [
            'campaigns' => $campaigns
        ]);
    }

    public function campaignList()
    {
        $campaigns = $this->campaignService->getCampaignList();
        foreach ($campaigns as $campaign) {
            $campaign->status = $this->getCampaignStatus($campaign->dateStart, $campaign->dateEnd);
        }

        $statistics = $this->campaignService->getCampaignStatistics();

        return view('admin.pages.campaign.index_list', [
            'campaigns' => $campaigns,
            'statistics' => $statistics
        ]);
    }

    public function campaignPendingList()
    {
        $campaigns = $this->campaignService->getPendingCampaignList();

        return view('admin.pages.campaign.index_pending', [
            'campaigns' => $campaigns
        ]);
    }

    public function updateStatus(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'campaign_id' => 'required|integer',
            'status' => 'required|integer'
        ]);

        $campaignId = $validated['campaign_id'];
        $status = $validated['status'];

        try {
            $success = $this->campaignService->updateStatus($campaignId, $status);

            if ($success) {
                return response()->json([
                    'message' => 'Trạng thái chiến dịch đã được cập nhật thành công.',
                    'status' => $status
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Không thể cập nhật trạng thái chiến dịch.',
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ], 500);
        }
    }



    private function getCampaignStatus($datestart, $dateend)
    {
        $now = Carbon::now();

        if ($now->lt(Carbon::parse($datestart))) {
            return 'upcoming';
        } elseif ($now->between(Carbon::parse($datestart), Carbon::parse($dateend))) {
            return 'ongoing';
        } else {
            return 'ended';
        }
    }

    public function getCampaignDetail($campaignId)
    {
        try {
            $campaign = $this->campaignService->getCampaignDetail($campaignId);

            // Tính toán statusText
            $now = Carbon::now();
            $dateStart = Carbon::parse($campaign->dateStart);
            $dateEnd = Carbon::parse($campaign->dateEnd);

            if ($now->lessThan($dateStart)) {
                $statusText = "Sắp diễn ra";
            } elseif ($now->greaterThan($dateEnd)) {
                $statusText = "Đã kết thúc";
            } else {
                $statusText = "Đang diễn ra";
            }

            return view('admin.pages.campaign.campaignDetail.index', [
                'campaign' => $campaign,
                'statusText' => $statusText,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.campaign.index')->withErrors('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function searchByProvince(Request $request)
    {
        $province = $request->query('province', 'Tất cả');

        $campaigns = $this->campaignService->searchCampaignsByProvince($province);

        foreach ($campaigns as $campaign) {
            $campaign->status = $this->getCampaignStatus($campaign->dateStart, $campaign->dateEnd);
        }

        $statistics = $this->campaignService->getCampaignStatistics();

        return view('admin.pages.campaign.index_list', [
            'campaigns' => $campaigns,
            'statistics' => $statistics
        ]);
    }

    public function searchByProvincePending(Request $request)
    {
        $province = $request->query('province', 'Tất cả');

        $campaigns = $this->campaignService->searchCampaignsPendingByProvince($province);

        return view('admin.pages.campaign.index_pending', [
            'campaigns' => $campaigns
        ]);
    }

    public function searchById(Request $request)
    {
        try {
            $campaignId = $request->input('campaignId');
            $campaigns = $this->campaignService->searchById($campaignId);
            foreach ($campaigns as $campaign) {
                $campaign->status = $this->getCampaignStatus($campaign->dateStart, $campaign->dateEnd);
            }
    
            $statistics = $this->campaignService->getCampaignStatistics();
    
            return view('admin.pages.campaign.index_list', [
                'campaigns' => $campaigns,
                'statistics' => $statistics
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.pages.campaign.list')->with('error', 'Chiến dịch không tồn tại.');
        }
    }

    public function searchPendingById(Request $request)
    {
        try {
            $campaignId = $request->input('campaignId');
            $campaigns = $this->campaignService->searchPendingById($campaignId);
    
            return view('admin.pages.campaign.index_pending', [
                'campaigns' => $campaigns
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.pages.campaign.list')->with('error', 'Chiến dịch không tồn tại.');
        }
    }
}
