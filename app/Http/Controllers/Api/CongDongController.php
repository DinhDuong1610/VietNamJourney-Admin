<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use DateTime;
use DateTimeZone;
use App\Models\InfoFormVolunteer;

class CongDongController extends Controller
{

    public function getSocialPosts(Request $request)
    {
        if ($request->isMethod('options')) {
            return response()->json(['message' => 'OK'], 200);
        }

        if ($request->isMethod('post')) {
            try {
                $posts = DB::table('post')
                    ->where('status', 1) // Thêm điều kiện status = 1
                    ->orderBy('Post_ID', 'desc')
                    ->get();

                $result = [];

                foreach ($posts as $post) {
                    // Lấy thông tin người dùng từ bảng user_information, bao gồm trường Name, Image và Check
                    $user = DB::table('user_information')
                        ->select('Name', 'Image', 'Check')
                        ->where('UserLogin_ID', $post->User_ID) // Thay đổi thành UserLogin_ID
                        ->first();

                    // Kiểm tra tồn tại người dùng và trường Check
                    if (!$user) {
                        continue; // Bỏ qua bài viết nếu người dùng không hợp lệ hoặc chưa được check
                    }

                    $likeCount = DB::table('islike')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $commentCount = DB::table('comment')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $result[] = [
                        'id' => $post->Post_ID,
                        'user_id' => $post->User_ID,
                        'content' => $post->Content,
                        'image' => $post->Image ? url($post->Image) : null,
                        'created_at' => $this->timeElapsedString($post->created_at),
                        'likes' => $likeCount,
                        'comments' => $commentCount,
                        'name' => $user->Name,
                        'avatar' => $user->Image ? url($user->Image) : null,
                        'check' => $user->Check, // Bao gồm trường Check trong thông tin người dùng
                    ];
                }

                return response()->json(['posts' => $result], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Lỗi khi kết nối đến cơ sở dữ liệu'], 500);
            }
        }
    }


    public function getSocialOutstanding(Request $request)
    {
        if ($request->isMethod('options')) {
            return response()->json(['message' => 'OK'], 200);
        }

        if ($request->isMethod('get')) {
            try {
                $outstandingUsers = DB::table('follow')
                    ->select('following_id', DB::raw('COUNT(*) as follow_count'))
                    ->groupBy('following_id')
                    ->orderBy('follow_count', 'desc')
                    ->limit(5)
                    ->get();

                $result = [];

                foreach ($outstandingUsers as $user) {
                    $userInfo = DB::table('user_information')
                        ->select('User_ID', 'username', 'image', 'check')
                        ->where('UserLogin_ID', $user->following_id)
                        ->first();

                    $result[] = [
                        'id' => $userInfo->User_ID,
                        'username' => $userInfo->username,
                        'check' => $userInfo->check,
                        'image' => $userInfo->image ? url($userInfo->image) : null,
                        'total_following' => $user->follow_count,
                    ];
                }

                return response()->json(['outstanding_users' => $result], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Lỗi khi kết nối đến cơ sở dữ liệu'], 500);
            }
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }

    private function timeElapsedString($datetime, $full = false)
    {
        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $now = new DateTime('now', $timezone);
        $ago = new DateTime($datetime, $timezone);
        $diff = $now->diff($ago);

        $string = [
            'y' => 'năm',
            'm' => 'tháng',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
        ];

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'vừa xong';
    }
    public function getTopGroup()
    {
        // Truy vấn bảng volunteer để lấy 6 campaignid có số hàng trả về nhiều nhất và số lượng hàng cho mỗi campaignid
        $topCampaigns = DB::table('volunteer')
            ->select('campaignid', DB::raw('count(*) as total'))
            ->groupBy('campaignid')
            ->orderBy('total', 'desc')
            ->limit(4)
            ->get();

        // Lấy danh sách các campaignid từ kết quả truy vấn volunteer
        $topCampaignIds = $topCampaigns->pluck('campaignid')->toArray();

        // Truy vấn bảng campaign để lấy thông tin image, id, name
        $topGroups = DB::table('campaign')
            ->whereIn('id', $topCampaignIds)
            ->select('id', 'name', DB::raw("CONCAT('" . url('/') . "/', image) AS image_url"))
            ->get();

        // Tạo một mảng kết hợp để lưu trữ số lượng hàng đối với mỗi campaignid
        $campaignsWithCounts = [];
        foreach ($topCampaigns as $campaign) {
            $campaignsWithCounts[$campaign->campaignid] = $campaign->total;
        }

        // Bổ sung số lượng hàng đối với mỗi campaignid vào kết quả của campaign
        foreach ($topGroups as $group) {
            $group->volunteer_count = $campaignsWithCounts[$group->id] ?? 0;
        }

        return response()->json([
            'top_groups' => $topGroups
        ]);
    }
    public function getCampaignUser(Request $request)
    {
        $user_id = $request->input('user_id'); // Nhận user_id từ client

        // Truy vấn bảng volunteer để lấy các campaignid có user_id tương ứng, lấy 5 dòng đầu, sắp xếp từ lớn đến nhỏ
        $campaignIds = DB::table('volunteer')
            ->where('userid', $user_id)
            ->orderByDesc('campaignid')
            ->limit(5)
            ->pluck('campaignid');

        // Truy vấn bảng campaign để lấy thông tin image, name, province
        $campaigns = DB::table('campaign')
            ->whereIn('id', $campaignIds)
            ->select('id', 'name', 'province', DB::raw("CONCAT('" . url('/') . "/', image) AS image_url"))
            ->get();

        return response()->json([
            'campaigns' => $campaigns
        ]);
    }

    public function getCampaignPosts(Request $request)
    {
        if ($request->isMethod('options')) {
            return response()->json(['message' => 'OK'], 200);
        }

        if ($request->isMethod('post')) {
            try {
                $campaign_id = $request->input('campaign_id');

                $posts = DB::table('post')
                    ->where('campaign_id', $campaign_id)
                    ->orderBy('Post_ID', 'desc')
                    ->get();

                $result = [];

                foreach ($posts as $post) {
                    // Lấy thông tin người dùng từ bảng user_information, bao gồm trường Name, Image và Check
                    $user = DB::table('user_information')
                        ->select('Name', 'Image', 'Check')
                        ->where('User_ID', $post->User_ID)
                        ->first();

                    // Kiểm tra tồn tại người dùng và trường Check
                    if (!$user) {
                        continue; // Bỏ qua bài viết nếu người dùng không hợp lệ hoặc chưa được check
                    }

                    $likeCount = DB::table('IsLike')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $commentCount = DB::table('comment')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $result[] = [
                        'id' => $post->Post_ID,
                        'user_id' => $post->User_ID,
                        'content' => $post->Content,
                        'image' => $post->Image ? url($post->Image) : null,
                        'created_at' => $this->timeElapsedString($post->created_at),
                        'likes' => $likeCount,
                        'comments' => $commentCount,
                        'name' => $user->Name,
                        'avatar' => $user->Image ? url($user->Image) : null,
                        'check' => $user->Check, // Bao gồm trường Check trong thông tin người dùng
                    ];
                }

                return response()->json(['posts' => $result], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Lỗi khi kết nối đến cơ sở dữ liệu'], 500);
            }
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }

    public function getCampaignPostsConfirm(Request $request)
    {
        if ($request->isMethod('options')) {
            return response()->json(['message' => 'OK'], 200);
        }

        if ($request->isMethod('post')) {
            try {
                $campaign_id = $request->input('campaign_id');

                $posts = DB::table('post')
                    ->where('campaign_id', $campaign_id)
                    ->where('campaign_status', '!=', 1)
                    ->orderBy('Post_ID', 'desc')
                    ->get();

                $result = [];

                foreach ($posts as $post) {
                    // Lấy thông tin người dùng từ bảng user_information, bao gồm trường Name, Image và Check
                    $user = DB::table('user_information')
                        ->select('Name', 'Image', 'Check')
                        ->where('User_ID', $post->User_ID)
                        ->first();

                    // Kiểm tra tồn tại người dùng và trường Check
                    if (!$user) {
                        continue; // Bỏ qua bài viết nếu người dùng không hợp lệ hoặc chưa được check
                    }

                    $likeCount = DB::table('IsLike')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $commentCount = DB::table('comment')
                        ->where('Post_ID', $post->Post_ID)
                        ->count();

                    $result[] = [
                        'id' => $post->Post_ID,
                        'user_id' => $post->User_ID,
                        'content' => $post->Content,
                        'image' => $post->Image ? url($post->Image) : null,
                        'created_at' => $this->timeElapsedString($post->created_at),
                        'likes' => $likeCount,
                        'comments' => $commentCount,
                        'name' => $user->Name,
                        'avatar' => $user->Image ? url($user->Image) : null,
                        'check' => $user->Check, // Bao gồm trường Check trong thông tin người dùng
                    ];
                }

                return response()->json(['posts' => $result], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Lỗi khi kết nối đến cơ sở dữ liệu'], 500);
            }
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }
    public function confirmCampaignPost(Request $request)
    {
        // Lấy post_id và status từ request
        $postId = $request->input('post_id');
        $status = $request->input('status');

        // Kiểm tra xem post_id và status có tồn tại trong request không
        if (!$postId || !$status) {
            return response()->json(['error' => 'post_id and status are required'], 400);
        }

        if ($status == 'yes') {
            // Thực hiện truy vấn để cập nhật campaign_status
            $updated = DB::table('post')
                ->where('post_id', $postId)
                ->update(['campaign_status' => 1]);

            // Kiểm tra xem có bản ghi nào được cập nhật hay không
            if ($updated) {
                return response()->json(['success' => 'Campaign status updated successfully.'], 200);
            } else {
                return response()->json(['error' => 'No post found with the given post_id or update failed.'], 404);
            }
        } elseif ($status === 'no') {
            // Thực hiện xóa dòng có post_id tương ứng
            $deleted = DB::table('post')
                ->where('post_id', $postId)
                ->delete();

            // Kiểm tra xem có bản ghi nào được xóa hay không
            if ($deleted) {
                return response()->json(['success' => 'Post deleted successfully.'], 200);
            } else {
                return response()->json(['error' => 'No post found with the given post_id or deletion failed.'], 404);
            }
        } else {
            return response()->json(['error' => 'Invalid status value.'], 400);
        }
    }
    public function checkAdminGroup(Request $request)
    {
        // Lấy group_id và user_id từ request
        $groupId = $request->input('group_id');
        $userId = $request->input('user_id');

        // Kiểm tra xem group_id và user_id có tồn tại trong request không
        if (!$groupId || !$userId) {
            return response()->json(['error' => 'group_id and user_id are required'], 400);
        }

        // Thực hiện truy vấn để kiểm tra xem có dòng nào với id = group_id và userId = user_id trong bảng campaign
        $campaign = DB::table('campaign')
            ->where('id', $groupId)
            ->where('userId', $userId)
            ->first();

        // Kiểm tra kết quả truy vấn và trả về "yes" hoặc "no"
        if ($campaign) {
            return response()->json(['result' => 'yes'], 200);
        } else {
            return response()->json(['result' => 'no'], 200);
        }
    }

    public function getInformationCampaign(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $campaignId = $request->input('campaign_id');

                // Truy vấn lấy thông tin chiến dịch
                $campaign = DB::table('campaign')
                    ->select('name', 'image', 'dateStart')
                    ->where('id', $campaignId)
                    ->first();

                // Lấy danh sách tình nguyện viên cùng với created_at, chỉ lấy những bản ghi có status = 2
                $volunteers = DB::table('volunteer')
                    ->where('campaignid', $campaignId)
                    ->where('status', 2)
                    ->get(['userID', 'created_at']);

                // Đếm số hàng mà volunteer trả về
                $quantity = $volunteers->count();

                // Lấy thông tin người dùng từ bảng user_information
                $volunteerDetails = [];
                foreach ($volunteers as $volunteer) {
                    $userInfo = DB::table('user_information')
                        ->select('name', 'image')
                        ->where('userlogin_id', $volunteer->userID)
                        ->first();

                    $volunteerDetails[] = [
                        'userID' => $volunteer->userID,
                        'created_at' => $volunteer->created_at,
                        'name' => $userInfo ? $userInfo->name : null,
                        'image' => $userInfo && $userInfo->image ? url($userInfo->image) : null,
                    ];
                }

                // Tạo mảng thông tin chiến dịch
                $campaignInfo = [
                    'name' => $campaign->name,
                    'image' => $campaign->image ? url($campaign->image) : null,
                    'dateStart' => $campaign->dateStart,
                    'volunteer_count' => $quantity, // Trả về số lượng tình nguyện viên
                    'volunteers' => $volunteerDetails,
                    'quantity' => $quantity
                ];

                return response()->json([
                    'campaign' => $campaignInfo, // Trả về số lượng hàng volunteer dưới tên biến quantity
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Lỗi khi kết nối đến cơ sở dữ liệu'], 500);
            }
        }

        return response()->json(['error' => 'Method not allowed'], 405);
    }
    public function addPostGroup(Request $request)
    {
        // Xác định múi giờ
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Validate input
        $validator = Validator::make($request->all(), [
            'User_ID' => 'required|integer',
            'campaign_id' => 'required|integer',
            'Content' => 'nullable|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 400);
        }

        $user_id = $request->input('User_ID');
        $content = $request->input('Content');
        $campaign_id = $request->input('campaign_id');
        $image = $request->file('Image');

        $imagePath = null;
        if ($image) {
            $imagePath = $image->store('image');
        }

        try {
            // Insert post into database
            DB::table('post')->insert([
                'User_ID' => $user_id,
                'Content' => $content,
                'Image' => $imagePath,
                'campaign_id' => $campaign_id,
                'created_at' => now()
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
