<?php

namespace App\Http\Services;

use App\Models\Volunteer;
use App\Models\User;

class VolunteerService extends BaseCrudService
{
    private Volunteer $volunteer;

    public function __construct(Volunteer $volunteer)
    {
        $this->volunteer = $volunteer;
        parent::__construct($volunteer);
    }

    public function getJoinedVolunteers($campaignId)
    {
        $volunteers = Volunteer::where('campaignId', $campaignId)
            ->where('status', 2)
            ->with('user.userInformation', 'formVolunteer.infoFormVolunteer')
            ->get();

        return $volunteers;
    }

    public function getPendingVolunteers($campaignId)
    {
        $volunteers = Volunteer::where('campaignId', $campaignId)
            ->where('status', 1)
            ->with('user.userInformation', 'formVolunteer.infoFormVolunteer')
            ->get();

        return $volunteers;
    }

}
