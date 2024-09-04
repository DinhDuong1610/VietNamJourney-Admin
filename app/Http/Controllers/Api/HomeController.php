<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\DashboardService;


class HomeController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function home()
    {
        $data = $this->dashboardService->home();

        return response()->json([
            'users' => $data['users'],
            'campaigns' => $data['campaigns'],
            'fun' => $data['fun'],
        ]);
    }
}
