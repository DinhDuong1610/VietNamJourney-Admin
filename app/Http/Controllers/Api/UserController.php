<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Follow;
use App\Models\Link;
use App\Models\Post;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function getInformationNavBar(Request $request)
    {
        $userId = $request->input('User_ID');

        $user = DB::table('user_information')->where('UserLogin_ID', $userId)->first();

        if ($user) {
            // Check if the user has an image
            if ($user->Image) {
                $user->Image = url($user->Image); // Convert the image path to URL
            }
            return response()->json(['success' => true, 'user' => $user]);
        } else {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }
    }
    public function getUserInformation(Request $request)
    {
        // Xử lý yêu cầu preflight
        if ($request->isMethod('options')) {
            return response('', 200);
        }

        $userId = $request->input('userId');
        $currentUserId = $request->input('currentUserId');

        // Kiểm tra nếu thiếu thông tin userId
        if (!$userId) {
            return response()->json(['error' => 'User ID không hợp lệ'], 400);
        }

        try {
            $user = DB::table('user_information as u')
                ->select(
                    'u.*',
                    DB::raw('(SELECT COUNT(*) FROM follow WHERE Following_ID = u.User_ID) AS followers'),
                    DB::raw('(SELECT COUNT(*) FROM follow WHERE Follower_ID = u.User_ID) AS following'),
                    'l.Link as facebookLink'
                )
                ->leftJoin('link as l', function ($join) {
                    $join->on('u.User_ID', '=', 'l.User_ID')
                        ->where('l.Social', 'Facebook');
                })
                ->where('u.User_ID', $userId)
                ->first();

            // Nếu không tìm thấy người dùng
            if (!$user) {
                return response()->json(['error' => 'Không tìm thấy người dùng'], 404);
            }

            // Kiểm tra trạng thái theo dõi
            $isFollowing = false;
            if ($currentUserId) {
                $isFollowing = DB::table('follow')
                    ->where('Follower_ID', $currentUserId)
                    ->where('Following_ID', $userId)
                    ->exists();
            }

            // Tạo response JSON
            $response = [
                'user' => [
                    'avatar' => $user->Image ? url($user->Image) : null,
                    'name' => $user->Name,
                    'username' => $user->Username,
                    'followers' => $user->followers,
                    'following' => $user->following,
                    'role' => $user->Role,
                    'location' => $user->LiveAt,
                    'facebookLink' => $user->facebookLink,
                    'isFollowing' => $isFollowing,
                    'check' => $user->check // Bao gồm trường Check trong thông tin người dùng
                ]
            ];

            // Trả về response thành công
            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Bắt lỗi và trả về thông báo lỗi
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateUserInfo(Request $request)
    {
        // Validate the request inputs
        $validator = Validator::make($request->all(), [
            'userId' => 'required|integer',
            'name' => 'nullable|string',
            'location' => 'nullable|string',
            'facebookLink' => 'nullable|string',
            'role' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 400);
        }

        // Get the validated inputs
        $userId = $request->input('userId');
        $name = $request->input('name');
        $location = $request->input('location');
        $facebookLink = $request->input('facebookLink');
        $role = $request->input('role');
        $avatar = $request->file('avatar');

        $avatarDbPath = null;
        if ($avatar) {
            // Store the avatar with a unique name in the 'storage/app/image' directory
            $avatarName = uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatarPath = $avatar->storeAs('image', $avatarName); // Stored in 'storage/app/image'

            // Prepare relative path for database storage
            $avatarDbPath = 'image/' . $avatarName; // Relative path saved in database
        }

        try {
            // Prepare data for update
            $updateData = [
                'Name' => $name,
                'LiveAt' => $location,
                'Role' => $role,
            ];

            // If avatar exists, add it to the update data
            if ($avatarDbPath) {
                $updateData['Image'] = $avatarDbPath;
            }

            // Update the user information
            DB::table('user_information')
                ->where('UserLogin_ID', $userId)
                ->update($updateData);

            // Update or insert Facebook link
            DB::table('link')
                ->updateOrInsert(
                    ['User_ID' => $userId, 'Social' => 'Facebook'],
                    ['Link' => $facebookLink]
                );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Database error: ' . $e->getMessage()], 500);
        }
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

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' trước' : 'vừa xong';
    }

    public function getPosts(Request $request)
    {
        // Lấy userId từ request
        $userId = $request->input('userId');

        if (!$userId) {
            return response()->json(['error' => 'User ID không hợp lệ'], 400);
        }

        try {
            // Lấy thông tin người dùng từ bảng user_information, bao gồm trường Image và Check
            $user = DB::table('user_information')
                ->where('User_ID', $userId)
                ->first(['User_ID', 'Name', 'Image', 'Check']);

            // Kiểm tra tồn tại người dùng
            if (!$user) {
                return response()->json(['error' => 'Người dùng không tồn tại'], 404);
            }

            // Lấy danh sách bài viết của người dùng và định dạng thời gian
            $posts = DB::table('post')
                ->where('User_ID', $userId)
                ->orderBy('Post_ID', 'DESC')
                ->get();

            $formattedPosts = [];
            foreach ($posts as $post) {
                // Định dạng thời gian của bài viết
                $created_at = $this->timeElapsedString($post->created_at);

                // Đếm tổng số lượt like và comment
                $likeCount = DB::table('islike')
                    ->where('Post_ID', $post->Post_ID)
                    ->count();

                $commentCount = DB::table('comment')
                    ->where('Post_ID', $post->Post_ID)
                    ->count();

                // Thêm vào mảng kết quả
                $formattedPosts[] = [
                    'id' => $post->Post_ID,
                    'user_id' => $post->User_ID,
                    'user_name' => $user->Name,
                    'user_avatar' => $user->Image ? url($user->Image) : null,
                    'content' => $post->Content,
                    'image' => $post->Image ? url($post->Image) : null,
                    'created_at' => $created_at,
                    'likes' => $likeCount,
                    'comments' => $commentCount,
                    // Thêm các thông tin khác của bài viết tại đây
                ];
            }

            // Trả về response thành công
            return response()->json([
                'user' => [
                    'id' => $user->User_ID,
                    'name' => $user->Name,
                    'avatar' => $user->Image ? url($user->Image) : null,
                    'check' => $user->Check, // Bao gồm trường Check trong thông tin người dùng
                ],
                'posts' => $formattedPosts
            ], 200);
        } catch (\Exception $e) {
            // Bắt lỗi và trả về thông báo lỗi
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addPost(Request $request)
    {
        // Xác định múi giờ
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        // Validate input
        $validator = Validator::make($request->all(), [
            'User_ID' => 'required|integer',
            'Content' => 'nullable|string',
            'Image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()], 400);
        }

        $user_id = $request->input('User_ID');
        $content = $request->input('Content');
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
                'created_at' => now()
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
