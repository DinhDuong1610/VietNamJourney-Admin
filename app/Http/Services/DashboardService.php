<?php

namespace App\Http\Services;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Post;
use Carbon\Carbon;
class DashboardService
{
    private User $user;
    private Campaign $campaign;
    private Post $post;

    public function __construct(User $user, Campaign $campaign, Post $post)
    {
        $this->user = $user;
        $this->campaign = $campaign;
        $this->post = $post;
    }

    public function dashboard()
    {
        $userss = $this->user
            ->with('userInformation')
            ->get();

        $users = $this->user
            ->with('userInformation')
            ->withCount('volunteer as joined')
            ->orderBy('joined', 'desc')
            ->paginate(4);

        $posts = $this->post
        ->where('status', 1)    
        ->get();

        $campaigns = $this->campaign->get();

        // Số liệu thống kê chiến dịch trong 12 tháng
        $campaignStatistics = [];
        $currentYear = Carbon::now()->year;
        for ($month = 1; $month <= 12; $month++) {
            $campaignCount = $this->campaign
                ->where('status', 1)
                ->whereMonth('dateStart', $month)
                ->whereYear('dateStart', $currentYear)
                ->count();
            $campaignStatistics[] = [
                'month' => $month,
                'count' => $campaignCount
            ];
        }

        // Tổng số tiền đã thu được từ tất cả các chiến dịch
        $totalMoney = $this->campaign->sum('totalMoney');

        // Số lượng người dùng tham gia tạo chiến dịch
        $userProfessional = $this->user
            ->whereHas('userInformation', function ($query) {
                $query->where('check', 1);
            })
            ->count();

        // Số lượng người dùng đã tham gia chiến dịch (không phải người tạo chiến dịch)
        $userJoined = $this->user
            ->whereHas('userInformation', function ($query) {
                $query->where('check', 0);
            })
            ->whereHas('volunteer')
            ->count();

        // Số lượng người dùng chưa tham gia chiến dịch nào
        $userNotJoined = $this->user
            ->whereHas('userInformation', function ($query) {
                $query->where('check', 0); 
            })
            ->doesntHave('volunteer') 
            ->count();

        // Tổng số tiền quyên góp và số lượng chiến dịch theo từng tỉnh thành
        $donationAndCampaignsByProvince = $this->campaign
            ->selectRaw('province, COUNT(*) as total_campaigns, SUM(totalMoney) as total_donation')
            ->groupBy('province')
            ->orderBy('total_campaigns', 'desc')
            ->paginate(5);

        return [
            'userss' => $userss,
            'users' => $users,
            'campaigns' => $campaigns,
            'campaignStatistics' => $campaignStatistics,
            'posts' => $posts,
            'totalMoney' => $totalMoney,
            'userProfessional' => $userProfessional,
            'userJoined' => $userJoined,
            'userNotJoined' => $userNotJoined,
            'donationAndCampaignsByProvince' => $donationAndCampaignsByProvince
        ];
    }
}
