<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MeetingController extends Controller
{
    public function index()
    {
        return response()->json(['status' => 'success']);
    }

    public function getMemberMeeting(Request $request)
    {
        $thread = $request->input('thread');
        try {
            // Truy vấn vào bảng 'joining' để lấy id_user, voice, date_join nơi mà thread = $thread
            $members = DB::table('joining')
                ->select('id_user', 'voice', 'date_join')
                ->where('thread', $thread)
                ->get();

            // Lấy các id_user từ kết quả truy vấn
            $userIds = $members->pluck('id_user')->toArray();

            // Truy vấn vào bảng 'user_information' để lấy Name và Image nơi mà userlogin_id = id_user
            $userInfo = DB::table('user_information')
                ->select('userlogin_id', 'Name', 'Image')
                ->whereIn('userlogin_id', $userIds)
                ->get()
                ->keyBy('userlogin_id');

            // Đường dẫn server đầy đủ để thêm vào trước đường dẫn hình ảnh
            $serverPath = url(''); // Hoặc dùng asset('/images/');

            // Truy vấn vào bảng 'meeting' để lấy campaign_id nơi mà thread = $thread
            $campaignId = DB::table('meeting')
                ->select('campaign_id')
                ->where('id', $thread)
                ->value('campaign_id');

            // Truy vấn vào bảng 'volunteer' để lấy userId nơi mà campaignid = campaign_id
            $adminIds = DB::table('campaign')
                ->select('userId')
                ->where('id', $campaignId)
                ->pluck('userId')->toArray();

            // Truy vấn vào bảng 'user_information' để lấy Name và Image nơi mà userlogin_id = userId
            $adminInfo = DB::table('user_information')
                ->select('userlogin_id', 'Name', 'Image')
                ->whereIn('userlogin_id', $adminIds)
                ->get()
                ->map(function ($admin) use ($serverPath) {
                    return [
                        'id_user' => $admin->userlogin_id,
                        'Name' => $admin->Name,
                        'Image' => $admin->Image ? $serverPath . '/' . $admin->Image : null,
                    ];
                });

            // Chuẩn bị dữ liệu trả về
            $data = $members->map(function ($member) use ($userInfo, $serverPath) {
                $user = $userInfo->get($member->id_user);

                return [
                    'id_user' => $member->id_user,
                    'voice' => $member->voice,
                    'date_join' => Carbon::parse($member->date_join)->format('m-d H:i'),
                    'Name' => $user ? $user->Name : null,
                    'Image' => $user && $user->Image ? $serverPath . '/' . $user->Image : null,
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $data,
                'admin' => $adminInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getMemberMeeting: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve meeting members.'], 500);
        }
    }
    public function getInformationMeeting(Request $request)
    {
        $thread = $request->input('thread');

        try {
            // Truy vấn vào bảng 'meeting' để lấy date và date_end nơi mà id = thread
            $meeting = DB::table('meeting')
                ->select('date', 'date_end', 'campaign_id')
                ->where('id', $thread)
                ->first();

            if (!$meeting) {
                return response()->json(['status' => 'error', 'message' => 'Meeting not found.'], 404);
            }

            $dateEnd = $meeting->date_end ? new \DateTime($meeting->date_end, new \DateTimeZone('Asia/Ho_Chi_Minh')) : null;


            // Trả về dữ liệu khi sự kiện sắp diễn ra
            $meetingDate = new \DateTime($meeting->date, new \DateTimeZone('Asia/Ho_Chi_Minh'));
            $currentDateTime = now()->setTimezone('Asia/Ho_Chi_Minh');

            // Trả về dữ liệu khi sự kiện đã trễ
            if ($dateEnd && $currentDateTime > $dateEnd) {
                return response()->json([
                    'status' => 'late',
                    'message' => 'Meeting is late.',
                    'current_time' => $currentDateTime->format('Y-m-d H:i:s'),
                    'meeting_date' => $meeting->date,
                    'date_end' => $meeting->date_end,
                ], 200);
            }

            // Trả về dữ liệu khi sự kiện sắp diễn ra
            if ($currentDateTime < $meetingDate) {
                return response()->json([
                    'status' => 'soon',
                    'message' => 'Meeting is upcoming.',
                    'current_time' => $currentDateTime->format('Y-m-d H:i:s'),
                    'meeting_date' => $meeting->date,
                    'date_end' => $meeting->date_end,
                ], 200);
            }

            // Truy vấn vào bảng 'campaign' để lấy name, province và userId nơi mà id = campaign_id
            $campaign = DB::table('campaign')
                ->select('name', 'province', 'userId') // Lấy thêm userId
                ->where('id', $meeting->campaign_id)
                ->first();

            if (!$campaign) {
                return response()->json(['status' => 'error', 'message' => 'Campaign not found.'], 404);
            }

            // Truy vấn vào bảng 'user_information' để lấy name và image nơi mà userlogin_id = userId
            $userInfo = DB::table('user_information')
                ->select('userlogin_id', 'Name', 'Image')
                ->where('userlogin_id', $campaign->userId)
                ->first();

            $admin = $userInfo ? [
                'userId' => $userInfo->userlogin_id,
                'name' => $userInfo->Name,
                'image' => $userInfo->Image,
            ] : null;

            // Chuẩn bị dữ liệu trả về
            $data = [
                'campaign_id' => $meeting->campaign_id,
                'date' => $meeting->date,
                'campaign' => [
                    'name' => $campaign->name,
                    'province' => $campaign->province,
                ],
                'admin' => $admin, // Thêm thông tin admin
            ];

            return response()->json([
                'status' => 'success',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getInformationMeeting: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Failed to retrieve meeting information.'], 500);
        }
    }

    public function checkMemberMeeting(Request $request)
    {
        // Lấy campaign_id và user_id từ request
        $campaignId = $request->input('campaign_id');
        $userId = $request->input('user_id');

        // Kiểm tra xem campaign_id và user_id có tồn tại trong request không
        if (!$campaignId || !$userId) {
            return response()->json(['error' => 'campaign_id and user_id are required'], 400);
        }

        // Thực hiện truy vấn để kiểm tra xem có dòng nào với userid = user_id và campaignid = campaign_id trong bảng volunteer
        $volunteer = DB::table('volunteer')
            ->where('userId', $userId)
            ->where('campaignid', $campaignId)
            ->first();

        // Kiểm tra kết quả truy vấn
        if ($volunteer) {
            // Nếu tồn tại, thực hiện truy vấn bảng user_information để lấy name và image
            $userInfo = DB::table('user_information')
                ->select('name', 'image')
                ->where('userlogin_id', $userId)
                ->first();

            return response()->json([
                'result' => 'yes',
                'name' => $userInfo ? $userInfo->name : null,
                'image' => $userInfo && $userInfo->image ? url($userInfo->image) : null,
            ], 200);
        } else {
            return response()->json(['result' => 'no'], 200);
        }
    }
    public function CreateMeeting(Request $request)
    {
        // Lấy campaign_id từ request
        $campaignId = $request->input('campaign_id');

        // Kiểm tra xem campaign_id có tồn tại trong request không
        if (!$campaignId) {
            return response()->json(['error' => 'campaign_id is required'], 400);
        }

        // Lấy thời gian hiện tại theo múi giờ Việt Nam (UTC+7)
        $currentDateTime = now()->setTimezone('Asia/Ho_Chi_Minh');

        // Thực hiện chèn vào bảng meeting với campaign_id và date
        $meetingId = DB::table('meeting')->insertGetId([
            'campaign_id' => $campaignId,
            'date' => $currentDateTime,
        ]);

        // Lấy hàng vừa mới chèn
        $newMeeting = DB::table('meeting')->where('id', $meetingId)->first();

        // Trả lại kết quả cho client
        return response()->json($newMeeting, 200);
    }

    public function scheduleMeeting(Request $request)
    {
        // Lấy campaign_id và date từ request
        $campaignId = $request->input('campaign_id');
        $date = $request->input('date');

        // Kiểm tra xem campaign_id và date có tồn tại không
        if (!$campaignId || !$date) {
            return response()->json(['error' => 'campaign_id and date are required'], 400);
        }

        // Xử lý date để đảm bảo định dạng đúng
        try {
            $date = Carbon::parse($date)->setTimezone('Asia/Ho_Chi_Minh');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format'], 400);
        }

        // Chèn vào bảng meeting
        $meetingId = DB::table('meeting')->insertGetId([
            'campaign_id' => $campaignId,
            'date' => $date,
        ]);

        // Lấy dữ liệu vừa mới chèn
        $newMeeting = DB::table('meeting')->find($meetingId);

        // Trả lại dữ liệu cho client
        return response()->json($newMeeting, 201);
    }
    public function closeMeeting(Request $request)
    {
        // Lấy thread từ request
        $thread = $request->input('thread');

        // Kiểm tra xem thread có tồn tại không
        if (!$thread) {
            return response()->json(['error' => 'Thread ID is required'], 400);
        }

        // Lấy thời gian hiện tại theo múi giờ Hồ Chí Minh
        $dateEnd = Carbon::now()->setTimezone('Asia/Ho_Chi_Minh');

        // Cập nhật trường date_end trong bảng meeting cho bản ghi với id = thread
        $updated = DB::table('meeting')
        ->where('id', $thread)
            ->update(['date_end' => $dateEnd]);

        // Kiểm tra xem có bản ghi nào được cập nhật không
        if ($updated) {
            // Lấy lại bản ghi vừa được cập nhật
            $updatedMeeting = DB::table('meeting')->find($thread);

            // Trả lại dữ liệu cho client
            return response()->json($updatedMeeting, 200);
        } else {
            return response()->json(['error' => 'Thread not found or update failed'], 404);
        }
    }
}