<?php

namespace App\Http\Services;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CampaignService extends BaseCrudService
{
    private Campaign $campaign;

    public function __construct(Campaign $campaign)
    {
        $this->campaign = $campaign;
        parent::__construct($campaign);
    }

    public function getCampaignList()
    {
        return $this->campaign
            ->where('status', 1)
            ->withCount([
                'volunteer as joined' => function ($query) {
                    $query->where('status', 2);
                },
                'volunteer as pending' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->orderBy('dateEnd')
            ->orderBy('province')
            ->paginate(6);
    }

    // public function getPendingCampaignList()
    // {
    //     return $this->campaign
    //         ->where('status', 0)
    //         ->with('userInformation')
    //         ->orderBy('dateEnd')
    //         ->orderBy('province')
    //         ->paginate(7);
    // }

    public function getPendingCampaignList()
    {
        return $this->campaign
            ->where('status', 0)
            ->with('user.userInformation') // Liên kết với thông tin người dùng
            ->orderBy('dateEnd')
            ->orderBy('province')
            ->paginate(7);
    }


    public function getCampaignStatistics()
    {
        $campaignIng = Campaign::where('dateStart', '<=', now())
            ->where('dateEnd', '>=', now())
            ->where('status', 1)
            ->count();

        $campaignWill = Campaign::where('dateStart', '>', now())
            ->where('status', 1)
            ->count();

        $campaignEd = Campaign::where('dateEnd', '<', now())
            ->where('status', 1)
            ->count();

        // Tính tổng số tiền đã thu được từ tất cả các chiến dịch
        $money = Campaign::sum('moneyByVNJN');

        return [
            'campaignIng' => $campaignIng,
            'campaignWill' => $campaignWill,
            'campaignEd' => $campaignEd,
            'money' => $money,
        ];
    }

    public function updateStatus(int $campaignId, int $newStatus): bool
    {
        // Tìm chiến dịch theo ID
        $campaign = $this->campaign->find($campaignId);

        // Nếu không tìm thấy chiến dịch, ném lỗi
        if (!$campaign) {
            throw new ModelNotFoundException('Chiến dịch không tồn tại.');
        }

        // Cập nhật trạng thái
        $campaign->status = $newStatus;

        // Lưu thay đổi vào cơ sở dữ liệu
        return $campaign->save();
    }

    public function getCampaignDetail(int $campaignId)
    {
        // Lấy chiến dịch theo ID kèm thông tin của người dùng
        return $this->campaign
            ->with('user.userInformation') 
            ->findOrFail($campaignId);
    }

    public function searchCampaignsByProvince(string $province)
    {
        return $this->campaign
            ->where('province', 'LIKE', '%' . $province . '%')
            ->withCount([
                'volunteer as joined' => function ($query) {
                    $query->where('status', 2);
                },
                'volunteer as pending' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->orderBy('dateEnd')
            ->paginate(6);
    }

    public function searchCampaignsPendingByProvince(string $province)
    {
        return $this->campaign
            ->where('province', 'LIKE', '%' . $province . '%')
            ->where('status', 0)
            ->with('user.userInformation')
            ->orderBy('dateEnd')
            ->paginate(6);
    }

    public function searchById(int $campaignId)
    {
        try {
            $campaign = $this->campaign
                ->withCount([
                    'volunteer as joined' => function ($query) {
                        $query->where('status', 2);
                    },
                    'volunteer as pending' => function ($query) {
                        $query->where('status', 1);
                    }
                ])
                ->findOrFail($campaignId);

            return [$campaign];
        } catch (ModelNotFoundException $e) {
            return [];
        }
    }

    public function searchPendingById(int $campaignId)
    {
        try {
            $campaign = $this->campaign
                ->where('status', 0)
                ->with('user.userInformation')
                ->findOrFail($campaignId);

            return [$campaign];
        } catch (ModelNotFoundException $e) {
            return [];
        }
    }



    //API

    public function getCampaignsIng($province)
    {
        try {
            $campaigns = $this->campaign
                ->where('province', $province)
                ->whereDate('dateStart', '<=', now())
                ->whereDate('dateEnd', '>=', now())
                ->get();

            if ($campaigns->isEmpty()) {
                return Response::json([
                    'error' => "Không tìm thấy chiến dịch thuộc tỉnh $province"
                ], 404);
            }

            $campaigns->transform(function ($campaign) {
                return $campaign;
            });

            return Response::json($campaigns, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Không thể lấy thông tin chiến dịch: ' . $e->getMessage());
            return Response::json([
                'error' => 'Không thể lấy thông tin chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignsWill($province)
    {
        try {
            $campaigns = $this->campaign
                ->where('province', $province)
                ->whereDate('dateStart', '>', now())
                ->get();

            if ($campaigns->isEmpty()) {
                return Response::json([
                    'error' => "Không tìm thấy chiến dịch thuộc tỉnh $province"
                ], 404);
            }

            $campaigns->transform(function ($campaign) {
                return $campaign;
            });

            return Response::json($campaigns, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Không thể lấy thông tin chiến dịch: ' . $e->getMessage());
            return Response::json([
                'error' => 'Không thể lấy thông tin chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignsEd($province)
    {
        try {
            $campaigns = $this->campaign
                ->where('province', $province)
                ->whereDate('dateEnd', '<', now())
                ->get();

            if ($campaigns->isEmpty()) {
                return Response::json([
                    'error' => "Không tìm thấy chiến dịch thuộc tỉnh $province"
                ], 404);
            }

            $campaigns->transform(function ($campaign) {
                return $campaign;
            });

            return Response::json($campaigns, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Không thể lấy thông tin chiến dịch: ' . $e->getMessage());
            return Response::json([
                'error' => 'Không thể lấy thông tin chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignDetail_API($id)
    {
        try {
            $campaign = $this->campaign->find($id);

            if (!$campaign) {
                return Response::json([
                    'error' => "Chiến dịch không tồn tại"
                ], 404);
            }

            return Response::json($campaign, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Không thể lấy thông tin chiến dịch: ' . $e->getMessage());
            return Response::json([
                'error' => 'Không thể lấy thông tin chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getCampaignStatistics_API($province)
    {
        if (!$province) {
            return Response::json(['error' => 'Thiếu tham số tên tỉnh'], 400);
        }

        try {
            $campaignIng = $this->campaign
                ->where('province', $province)
                ->whereDate('dateStart', '<=', now())
                ->whereDate('dateEnd', '>=', now())
                ->count();

            $campaignWill = $this->campaign
                ->where('province', $province)
                ->whereDate('dateStart', '>', now())
                ->count();

            $campaignEd = $this->campaign
                ->where('province', $province)
                ->whereDate('dateEnd', '<', now())
                ->count();

            $money = $this->campaign
                ->where('province', $province)
                ->sum('moneyByVNJN');

            $statistics = [
                'campaignIng' => $campaignIng,
                'campaignWill' => $campaignWill,
                'campaignEd' => $campaignEd,
                'money' => $money,
            ];

            return Response::json($statistics, 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('Không thể lấy thông tin thống kê chiến dịch: ' . $e->getMessage());
            return Response::json([
                'error' => 'Không thể lấy thông tin thống kê chiến dịch: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getManagerCampaigns($userId)
    {
        $currentDate = Carbon::now()->toDateString();

        $campaignList = $this->campaign
            ->withCount([
                'volunteer as joined' => function ($query) {
                    $query->where('status', 2);
                },
                'volunteer as pending' => function ($query) {
                    $query->where('status', 1);
                }
            ])
            ->where('userId', $userId)
            ->orderBy('dateEnd')
            ->get();

        $response = [];

        if ($campaignList->isNotEmpty()) {
            foreach ($campaignList as $campaign) {
                if ($campaign->dateStart > $currentDate) {
                    $status = 'sắp diễn ra';
                } elseif ($campaign->dateEnd < $currentDate) {
                    $status = 'đã kết thúc';
                } else {
                    $status = 'đang diễn ra';
                }

                $response['list'][] = [
                    "id" => $campaign->id,
                    "name" => $campaign->name,
                    "province" => $campaign->province,
                    "district" => $campaign->district,
                    "status" => $status,
                    "joined" => $campaign->joined,
                    "pending" => $campaign->pending
                ];
            }
        } else {
            $response['error'] = 'Không có chiến dịch nào';
        }

        return $response;
    }

    public function getVolunteerStatus($userId, $campaignId)
    {
        $status = DB::table('volunteer')
            ->where('userId', $userId)
            ->where('campaignId', $campaignId)
            ->value('status'); 

        return $status;
    }
}
