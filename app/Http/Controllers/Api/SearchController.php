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
                    ->select('UserLogin_ID as User_ID', 'Username', 'Image')
                    ->limit(10)
                    ->get();

                $formattedUsers = $users->map(function ($user) {
                    return [
                        'User_ID' => $user->User_ID,
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

                $result = [];

                foreach ($posts as $post) {
                    $user = DB::table('user_information')
                        ->select('Name', 'Image')
                        ->where('User_ID', $post->User_ID)
                        ->first();

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
                        'name' => $user->Name,
                        'avatar' => $user->Image ? url($user->Image) : null,
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