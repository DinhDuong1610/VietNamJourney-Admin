<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\FunService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FunController extends Controller
{
    private FunService $funService;

    public function __construct(FunService $funService)
    {
        $this->funService = $funService;
    }

    public function funManager()
    {
        $data = $this->funService->funManager();

        foreach ($data['funCampaigns'] as $campaign) {
            $campaign->status = $this->getCampaignStatus($campaign->dateStart, $campaign->dateEnd);
        }

        return view('admin.pages.fun.index', [
            'funCurrent' => $data['funCurrent'],
            'funOfVNJN' => $data['funOfVNJN'],
            'funOfCampaign' => $data['funOfCampaign'],
            'funStatistics'=> $data['funStatistics'],
            'funCampaigns'=> $data['funCampaigns'],
        ]);
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
}
