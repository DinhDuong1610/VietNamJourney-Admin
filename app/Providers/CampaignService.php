<?php

namespace App\Providers;

use App\Models\Campaign;

class CampaignService extends BaseCrudService
{
    private Campaign $campaign;
    
    public function __construct(Campaign $campaign)
    {
        parent::__construct($campaign);
    }
}

