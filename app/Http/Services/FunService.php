<?php

namespace App\Http\Services;

use App\Models\Fun;
use App\Models\Campaign;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FunService extends BaseCrudService
{
    private Fun $fun;
    private Campaign $campaign;

    public function __construct(Fun $fun, Campaign $campaign)
    {
        $this->fun = $fun;
        $this->campaign = $campaign;
    }



    // Lấy tổng 'amount' từ bảng 'fun' khi 'campaignId' IS NULL
    public function sumAmountWithoutCampaign()
    {
        return $this->fun
            ->whereNull('campaignId')
            ->sum('amount');
    }

    public function funManager() {
        $funOfVNJN = $this->sumAmountWithoutCampaign();
        
        $funOfCampaign = $this->sumAmountByCampaign();

        $funCurrent = $funOfVNJN - ($this->sumTotalMoneyFromCampaign() - $funOfCampaign);

        $funStatistics = [];
        $currentYear = Carbon::now()->year;
        for ($month = 1; $month <= 12; $month++) {
            $funAmount = $this->fun
                ->whereMonth('time', $month)
                ->whereYear('time', $currentYear)
                ->sum('amount');
            $funStatistics[] = [
                'month' => $month,
                'amount' => $funAmount,
            ];
        }

        $funCampaigns = $this->campaign
        ->where('status', 1)
        ->withSum('fun as amount', 'amount')
        ->with('fun')
        ->orderBy('dateEnd', 'desc')
        ->orderBy('province')
        ->paginate(6);

        return [
            'funCurrent'=> $funCurrent, 
            'funOfVNJN'=> $funOfVNJN, 
            'funOfCampaign'=> $funOfCampaign,
            'funStatistics'=> $funStatistics,
            'funCampaigns'=> $funCampaigns
        ];
    }

    // Lấy tổng 'amount' từ bảng 'fun' khi 'campaignId' không null
    public function sumAmountByCampaign()
    {
        return $this->fun
            ->whereNotNull('campaignId')
            ->sum('amount');
    }

    // Lấy tổng 'totalMoney' từ bảng 'campaign'
    public function sumTotalMoneyFromCampaign()
    {
        return $this->campaign
            ->sum('totalMoney');
    }

    








    public function getFunWithoutCampaign()
    {
        $funs = $this->fun
            ->whereNull('campaignId')
            ->get();
        return $funs;
    }

    public function getFunByCampaign($campaignId)
    {
        $funs = $this->fun
            ->where('campaignId', $campaignId)
            ->get();
        return $funs;
    }
}
