<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function dashboard()
    {
        $data = $this->dashboardService->dashboard();

        return view('admin.pages.dashboard.index', [
            'userss' => $data['userss'],
            'users' => $data['users'],
            'campaigns' => $data['campaigns'],
            'campaignStatistics' => $data['campaignStatistics'],
            'posts' => $data['posts'],
            'totalMoney' => $data['totalMoney'],
            'userProfessional' => $data['userProfessional'],
            'userJoined' => $data['userJoined'],
            'userNotJoined' => $data['userNotJoined'],
            'donationAndCampaignsByProvince' => $data['donationAndCampaignsByProvince'],
        ]);
    }
}
