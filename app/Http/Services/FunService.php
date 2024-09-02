<?php

namespace App\Http\Services;

use App\Models\Fun;

class FunService extends BaseCrudService
{
    private Fun $fun;

    public function __construct(Fun $fun)
    {
        $this->fun = $fun;
        parent::__construct($fun);
    }

    public function getFunWithoutCampaign()
    {
        $funs = Fun::whereNull('campaignId')->get();
        return $funs;
    }

    public function getFunByCampaign($campaignId)
    {
        $funs = Fun::where('campaignId', $campaignId)->get();
        return $funs;
    }

}
