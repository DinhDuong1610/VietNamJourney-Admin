<?php

namespace App\Http\Services;

use App\Models\Campaign;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;



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

    public function createCampaign(Request $request)
    {
        // // Xác thực dữ liệu đầu vào
        // $validator = Validator::make($request->all(), [
        //     'userId' => 'required|numeric',
        //     'name' => 'required|string|max:255',
        //     'province' => 'required|string|max:255',
        //     'district' => 'required|string|max:255',
        //     'location' => 'required|string', // Tạm thời là string, sau sẽ decode JSON
        //     'dateStart' => 'required|date',
        //     'dateEnd' => 'required|date',
        //     'totalMoney' => 'required|numeric',
        //     'moneyByVNJN' => 'required|numeric',
        //     'timeline' => 'required|string', // Tạm thời là string, sau sẽ decode JSON
        //     'infoContact' => 'required|string', // Tạm thời là string, sau sẽ decode JSON
        //     'infoOrganization' => 'required|string', // Tạm thời là string, sau sẽ decode JSON
        //     'image' => 'required|file|mimes:jpeg,png,jpg,gif|max:51200',
        //     'description' => 'required|string',
        //     'plan' => 'required|string',
        // ]);

        // // Kiểm tra nếu dữ liệu không hợp lệ
        // if ($validator->fails()) {
        //     return ['error' => $validator->errors()];
        // }

        // Xử lý file ảnh và lưu vào thư mục storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('image', $imageName);
        } else {
            return ['error' => 'File ảnh không hợp lệ hoặc không tồn tại'];
        }

        try {
            DB::beginTransaction();

            $campaign = new Campaign();
            $campaign->userid = $request->userid;
            $campaign->name = $request->name;
            $campaign->province = $request->province;
            $campaign->district = $request->district;
            // $campaign->location = $request->location;
            $campaign->location = json_decode($request->location, true);
            $campaign->dateStart = $request->dateStart;
            $campaign->dateEnd = $request->dateEnd;
            $campaign->totalMoney = $request->totalMoney;
            $campaign->moneyByVNJN = $request->moneyByVNJN;
            $campaign->timeline = json_decode($request->timeline, true); // Giải mã JSON
            $campaign->infoContact = json_decode($request->infoContact, true); // Giải mã JSON
            $campaign->infoOrganization = json_decode($request->infoOrganization, true); // Giải mã JSON
            $campaign->image = $imagePath;
            $campaign->description = $request->description;
            $campaign->plan = $request->plan;
            $campaign->status = 0;

            $campaign->save();
            DB::commit();

            return ['success' => 'Thêm chiến dịch thành công', 'campaign' => $campaign];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Không thể thêm chiến dịch: ' . $e->getMessage());
            return ['error' => 'Không thể thêm chiến dịch: ' . $e->getMessage()];
        }
    }

    public function updateCampaign($request)
    {
        try {
            DB::beginTransaction();

            // Tìm chiến dịch theo ID
            $campaign = Campaign::find($request->id);
            if (!$campaign) {
                return ['status' => 404, 'error' => 'Chiến dịch không tồn tại'];
            }

            // Cập nhật các thuộc tính của chiến dịch
            if ($request->has('name')) $campaign->name = $request->name;
            if ($request->has('province')) $campaign->province = $request->province;
            if ($request->has('district')) $campaign->district = $request->district;
            // if ($request->has('location')) $campaign->location = $request->location;
            if ($request->has('location')) $campaign->location = json_decode($request->location, true);
            if ($request->has('dateStart')) $campaign->dateStart = $request->dateStart;
            if ($request->has('dateEnd')) $campaign->dateEnd = $request->dateEnd;
            if ($request->has('totalMoney')) $campaign->totalMoney = $request->totalMoney;
            if ($request->has('moneyByVNJN')) $campaign->moneyByVNJN = $request->moneyByVNJN;
            if ($request->has('timeline')) $campaign->timeline = json_decode($request->timeline, true);
            if ($request->has('infoContact')) $campaign->infoContact = json_decode($request->infoContact, true);
            if ($request->has('infoOrganization')) $campaign->infoOrganization = json_decode($request->infoOrganization, true);
            if ($request->has('description')) $campaign->description = $request->description;
            if ($request->has('plan')) $campaign->plan = $request->plan;

            // Xử lý file ảnh (nếu có)
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('image', $imageName);
                $campaign->image = $imagePath;
            }

            // Lưu các thay đổi vào cơ sở dữ liệu
            $campaign->save();
            DB::commit();

            return ['status' => 200, 'campaign' => $campaign];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 500, 'error' => 'Không thể cập nhật chiến dịch: ' . $e->getMessage()];
        }
    }

}
