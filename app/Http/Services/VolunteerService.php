<?php

namespace App\Http\Services;

use App\Models\Volunteer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\InfoFormVolunteer;
use App\Models\FormVolunteer;
use Illuminate\Support\Facades\Log;


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

    public function registerVolunteer($data)
    {
        try {
            // Tạo bản ghi cho InfoFormVolunteer
            $infoFormVolunteer = InfoFormVolunteer::create([
                'fullname' => $data['fullname'],
                'birth' => $data['birth'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
            ]);

            // Tạo bản ghi cho FormVolunteer
            $formVolunteer = FormVolunteer::create([
                'infoId' => $infoFormVolunteer->id,
                'reason' => $data['reason'],
            ]);

            // Tạo bản ghi cho Volunteer
            $volunteer = Volunteer::create([
                'userId' => $data['userId'],
                'campaignId' => $data['campaignId'],
                'status' => $data['status'],
                'formId' => $formVolunteer->id,
            ]);

            return [
                'message' => 'Đăng ký tham gia thành công',
                'volunteer' => $volunteer
            ];
        } catch (\Exception $e) {
            Log::error('Đăng ký thất bại: ' . $e->getMessage());
            throw new \Exception('Đăng ký tham gia thất bại: ' . $e->getMessage());
        }
    }

    public function updateVolunteerStatus($campaignId, $userId, $status)
    {
        try {
            $affected = Volunteer::where('campaignId', $campaignId)
                ->where('userId', $userId)
                ->update(['status' => $status]);

            return $affected;
        } catch (\Exception $e) {
            Log::error('Error updating volunteer status: ' . $e->getMessage());
            throw new \Exception('Unable to update volunteer status: ' . $e->getMessage());
        }
    }

}
