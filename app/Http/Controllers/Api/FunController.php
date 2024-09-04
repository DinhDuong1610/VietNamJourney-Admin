<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Services\FunService;

class FunController extends Controller
{
    private FunService $funService;

    public function __construct(FunService $funService)
    {
        $this->funService = $funService;
    }

    public function getFunWithoutCampaign(Request $request)
    {
        $funs = $this->funService->getFunWithoutCampaign();
        return response()->json(['funs' => $funs]);
    }

    public function getByCampaign(Request $request, $campaignId)
    {
        $data = $this->funService->getFunByCampaign($campaignId);

        return response()->json([
            'funs' => $data['funs'],
            'totalAmount' => $data['totalAmount'],
            'countAmount' => $data['countAmount'],
        ]);
    }
}
