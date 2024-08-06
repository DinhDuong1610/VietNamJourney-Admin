<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function getListSearch(Request $request)
    {
        $postInfo = $request->input('post_info');
        $userInfo = $request->input('user_info');

        try {
            $response = [];

            if ($userInfo) {
                // Tách các từ trong biến userInfo thành mảng các từ khoá
                $userKeywords = explode(' ', $userInfo);

                $users = DB::table('user_information')
                    ->where(function ($query) use ($userKeywords) {
                        foreach ($userKeywords as $keyword) {
                            $query->orWhere('Username', 'LIKE', '%' . $keyword . '%')
                                ->orWhere('Name', 'LIKE', '%' . $keyword . '%');
                        }
                    })
                    ->select('userlogin_id', 'Username', 'Image')
                    ->limit(10)
                    ->get();

                $formattedUsers = $users->map(function ($user) {
                    return [
                        'User_ID' => $user->userlogin_id,
                        'Username' => $user->Username,
                        'Image' => $user->Image ? url($user->Image) : null,
                    ];
                });

                $response['users'] = $formattedUsers;
            } else {
                $response['users'] = [];
            }

            if ($postInfo) {
                // Tách các từ trong biến postInfo thành mảng các từ khoá
                $postKeywords = explode(' ', $postInfo);

                $postsQuery = DB::table('post');

                $postsQuery->where(function ($query) use ($postKeywords) {
                    foreach ($postKeywords as $keyword) {
                        $query->orWhere('Content', 'LIKE', '%' . $keyword . '%');
                    }
                });

                $posts = $postsQuery->orderBy('Post_ID', 'desc')->limit(10)->get();

                // Lấy tất cả user_information liên quan đến bài viết
                $userIds = $posts->pluck('User_ID')->toArray();
                $users = DB::table('user_information')
                    ->whereIn('userlogin_id', $userIds)
                    ->select('userlogin_id', 'Name', 'Image')
                    ->get()
                    ->keyBy('userlogin_id');

                $result = [];

                foreach ($posts as $post) {
                    $user = $users->get($post->User_ID);
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
                        'createdAt' => $this->timeElapsedString($post->created_at),
                        'likes' => $likeCount,
                        'comments' => $commentCount,
                        'name' => $user ? $user->Name : null,
                        'avatar' => $user && $user->Image ? url($user->Image) : null,
                    ];
                }

                $response['posts'] = $result;
            } else {
                $response['posts'] = [];
            }

            return response()->json($response, 200);
        } catch (\Exception $e) {
            // Log chi tiết lỗi
            Log::error('Failed to retrieve data', ['error' => $e]);

            // Trả về phản hồi lỗi chi tiết
            return response()->json(['error' => 'Failed to retrieve data', 'message' => $e->getMessage()], 500);
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
}
