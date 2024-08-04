<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use App\Models\ChatGroupMessage;
use App\Models\ChatUser;
use App\Models\ChatBox;
use App\Models\User;

class ChatController extends Controller
{
    public function getUsersChat(Request $request)
    {
        $user_from = $request->input('user_from');

        // Step 1: Query chat_box table
        $chats = DB::table('chat_box')
            ->where('user_1', $user_from)
            ->orWhere('user_2', $user_from)
            ->get();

        $results = [];

        foreach ($chats as $chat) {
            // Determine user_to and other_user_id
            $user_to = ($chat->user_1 == $user_from) ? $chat->user_2 : $chat->user_1;
            $other_user_id = ($chat->user_1 == $user_from) ? $chat->user_2 : $chat->user_1;

            // Step 2: Query user_information table
            $user_info = DB::table('user_information')
                ->select('userlogin_id', 'name', 'image')
                ->where('userlogin_id', $other_user_id)
                ->first();

            if ($user_info) {
                // Append server path to image
                $image_url = $user_info->image ? asset($user_info->image) : null;

                // Step 3: Query chat_user table for latest content
                $latest_message = DB::table('chat_user')
                    ->select('id', 'Content')
                    ->where(function ($query) use ($user_from, $user_to) {
                        $query->where('user_from', $user_from)
                            ->where('user_to', $user_to);
                    })
                    ->orWhere(function ($query) use ($user_from, $user_to) {
                        $query->where('user_from', $user_to)
                            ->where('user_to', $user_from);
                    })
                    ->orderBy('id', 'desc')
                    ->first();

                // Prepare result
                $result = [
                    'user_to' => $user_info->userlogin_id,
                    'user_name' => $user_info->name,
                    'user_image' => $image_url,
                    'latest_content' => $latest_message ? $latest_message->Content : null,
                    'latest_message_id' => $latest_message ? $latest_message->id : null,
                ];

                $results[] = $result;
            }
        }

        // Sort results by latest_message_id descending
        usort($results, function ($a, $b) {
            return $b['latest_message_id'] - $a['latest_message_id'];
        });

        return response()->json(['chats' => $results]);
    }
    public function getChats(Request $request)
    {
        try {
            $user_to = $request->input('user_to');
            $user_from = $request->input('user_from');

            // Kiểm tra nếu user_to và user_from giống nhau hoặc user_to là 0, không thực hiện truy vấn và trả về lỗi
            if ($user_to == $user_from || $user_to == 0) {
                return response()->json(['error' => 'Invalid user_to'], 400);
            }

            // Truy vấn để lấy thông tin từ bảng user_information cho người nhận (user_to)
            $userToInfo = DB::table('user_information')
                ->where('UserLogin_ID', $user_to)
                ->first(['Name as name', 'Image as image']);

            // Truy vấn để lấy thông tin từ bảng user_information cho người gửi (user_from)
            $userFromInfo = DB::table('user_information')
                ->where('UserLogin_ID', $user_from)
                ->first(['Name as name', 'Image as image']);

            // Kiểm tra xem có thông tin trong chat_box hay chưa
            $existingChat = DB::table('chat_box')
                ->where(function ($query) use ($user_from, $user_to) {
                    $query->where('user_1', $user_from)
                        ->where('user_2', $user_to);
                })
                ->orWhere(function ($query) use ($user_from, $user_to) {
                    $query->where('user_1', $user_to)
                        ->where('user_2', $user_from);
                })
                ->first();

            // Nếu không có dữ liệu và user_to không phải là user_from, thực hiện insert vào chat_box
            if (!$existingChat && $user_to != $user_from) {
                DB::table('chat_box')->insert([
                    'user_1' => $user_from,
                    'user_2' => $user_to,
                    // Các trường thông tin khác có thể cần thiết
                ]);
            }

            // Truy vấn để lấy thông tin từ bảng chat_user
            $chats = DB::table('chat_user')
                ->where(function ($query) use ($user_from, $user_to) {
                    $query->where('user_from', $user_from)
                        ->where('user_to', $user_to);
                })
                ->orWhere(function ($query) use ($user_from, $user_to) {
                    $query->where('user_from', $user_to)
                        ->where('user_to', $user_from);
                })
                ->get([
                    'id',
                    'user_from',
                    'user_to',
                    'image',
                    'content',
                    'created_at'
                ]);

            foreach ($chats as $chat) {
                // Chỉ lấy ngày và giờ từ created_at

                if ($chat->image) {
                    $chat->image = url($chat->image);
                }
            }

            // Thêm đường dẫn đầy đủ vào thuộc tính image của userToInfo và userFromInfo
            if ($userToInfo && $userToInfo->image) {
                $userToInfo->image = url($userToInfo->image);
            }
            if ($userFromInfo && $userFromInfo->image) {
                $userFromInfo->image = url($userFromInfo->image);
            }

            // Trả về dữ liệu đã được tách
            return response()->json([
                'userToInfo' => $userToInfo,
                'userFromInfo' => $userFromInfo,
                'chats' => $chats
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching chats: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to fetch chats'], 500);
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

    public function sendMessage(Request $request)
    {
        try {
            $user_from = $request->input('user_from');
            $user_to = $request->input('user_to');
            $content = $request->input('content');
            $image = $request->file('image');
            $imagePath = null;

            if ($image) {
                $avatarName = uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('image', $avatarName);
            }

            $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
            $currentDateTime = new DateTime('now', $timezone);

            // Truy vấn tin nhắn gần nhất giữa hai người dùng
            $lastMessage = DB::table('chat_user')
                ->where(function ($query) use ($user_from, $user_to) {
                    $query->where('user_from', $user_from)
                        ->where('user_to', $user_to);
                })
                ->orWhere(function ($query) use ($user_from, $user_to) {
                    $query->where('user_from', $user_to)
                        ->where('user_to', $user_from);
                })
                ->orderBy('created_at', 'desc')
                ->first();

            // Kiểm tra thời gian chênh lệch giữa tin nhắn mới và tin nhắn gần nhất
            $shouldInsertTimestamp = true;
            if ($lastMessage) {
                $lastMessageTime = new DateTime($lastMessage->created_at, $timezone);
                $interval = $currentDateTime->diff($lastMessageTime);
                $minutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;

                if ($minutes < 10) {
                    $shouldInsertTimestamp = false;
                }
            }

            // Truy vấn thông tin user_from từ bảng user_information
            $userFromInfo = DB::table('user_information')
                ->where('userlogin_id', $user_from)
                ->select('name', 'image')
                ->first();

            // Truy vấn thông tin user_to từ bảng user_information
            $userToInfo = DB::table('user_information')
                ->where('userlogin_id', $user_to)
                ->select('name', 'image')
                ->first();

            $newMessageData = [
                'user_from' => $user_from,
                'user_to' => $user_to,
                'content' => $content,
                'image' => $imagePath,
            ];

            if ($shouldInsertTimestamp) {
                $newMessageData['created_at'] = $currentDateTime;
            }

            $newMessageId = DB::table('chat_user')->insertGetId($newMessageData);

            $newMessage = DB::table('chat_user')->where('id', $newMessageId)->first();

            // Format lại thời gian created_at nếu có
            if ($newMessage->created_at) {
                $newMessage->created_at = Carbon::parse($newMessage->created_at)->format('d-m H:i');
            }

            // Thêm thông tin user_from và user_to vào newMessage với đường dẫn URL đầy đủ
            $newMessage->user_from = (string) $user_from;
            $newMessage->user_to = (string) $user_to;
            $newMessage->user_name = $userFromInfo->name;
            $newMessage->user_image = url($userFromInfo->image);
            $newMessage->user_name_from = $userToInfo->name;
            $newMessage->user_image_from = url($userToInfo->image);

            // Thêm đường dẫn URL cho image vừa chèn
            if ($newMessage->image) {
                $newMessage->image = url($newMessage->image);
            }

            return response()->json($newMessage);
        } catch (\Exception $e) {
            Log::error('Error sending message: ' . $e->getMessage());
            return response()->json(['error' => 'Unable to send message'], 500);
        }
    }
    public function getOnlineUser(Request $request)
    {
        $user_id = $request->input('user_id');

        $users = DB::table('chat_box')
            ->where('user_1', $user_id)
            ->orWhere('user_2', $user_id)
            ->get()
            ->map(function ($chat) use ($user_id) {
                return $chat->user_1 == $user_id ? $chat->user_2 : $chat->user_1;
            });

        return response()->json(['onlineUsers' => $users]);
    }
    public function getGroupUser(Request $request)
    {
        $userId = $request->input('user_id');

        // Lấy các campaignId từ bảng volunteer
        $campaignIds = DB::table('volunteer')
            ->where('userid', $userId)
            ->pluck('campaignId');

        // Lấy thông tin campaign từ bảng campaign và tin nhắn cuối cùng từ bảng chatgroup_message
        $campaigns = DB::table('campaign')
            ->leftJoin('chatgroup_message', function ($join) {
                $join->on('campaign.id', '=', 'chatgroup_message.campaign_id')
                    ->whereIn('chatgroup_message.id', function ($query) {
                        $query->select(DB::raw('MAX(id)'))
                            ->from('chatgroup_message')
                            ->groupBy('campaign_id');
                    });
            })
            ->whereIn('campaign.id', $campaignIds)
            ->select('campaign.id as campaignId', 'campaign.name', 'campaign.image', 'chatgroup_message.content as last_message', 'chatgroup_message.id as message_id')
            ->orderBy('message_id', 'desc') // Sắp xếp theo id của chatgroup_message giảm dần
            ->get();

        // Xử lý đường dẫn đầy đủ của ảnh
        foreach ($campaigns as $campaign) {
            $campaign->image = url("{$campaign->image}");
        }

        return response()->json(['campaigns' => $campaigns]);
    }

    public function getGroupChats(Request $request)
    {
        $group_id = $request->input('group_id');
        $user_id = $request->input('user_id');

        // Truy vấn kiểm tra xem user có thuộc group này hay không
        $isMember = DB::table('volunteer')
            ->where('UserID', $user_id)
            ->where('campaignid', $group_id)
            ->exists();

        if (!$isMember) {
            return response()->json(['status' => 'not_member']);
        }

        // Truy vấn lấy thông tin nhóm
        $groupInfo = DB::table('campaign')
            ->select('province', 'name', 'image')
            ->where('id', $group_id)
            ->first();

        if ($groupInfo && $groupInfo->image) {
            $groupInfo->image = url($groupInfo->image);
        }

        // Truy vấn lấy tin nhắn trong group
        $chats = DB::table('chatgroup_message')
            ->select('user_from', 'content', 'image', 'created_at')
            ->where('campaign_id', $group_id)
            ->orderBy('created_at', 'asc')
            ->get();

        // Truy vấn lấy thông tin user và xử lý chuyển đổi đường dẫn hình ảnh
        $chats = $chats->map(function ($chat) {
            // Truy vấn thông tin user
            $user = DB::table('user_information')
                ->select('name', 'image')
                ->where('userlogin_id', $chat->user_from)
                ->first();

            // Gắn thông tin user vào chat
            $chat->user_name = $user ? $user->name : 'Unknown';
            $chat->user_image = $user && $user->image ? url($user->image) : null;

            // Chuyển đổi đường dẫn hình ảnh của tin nhắn nếu có
            if ($chat->image) {
                $chat->image = url($chat->image);
            }

            // Chuyển đổi định dạng thời gian

            return $chat;
        });

        return response()->json(['groupInfo' => $groupInfo, 'chats' => $chats]);
    }


    public function sendMessageGroup(Request $request)
    {
        $group_id = $request->input('group_id');
        $user_id = $request->input('user_id');
        $content = $request->input('message');
        $image = $request->file('image');

        // Đường dẫn ảnh
        $imagePath = null;
        if ($image) {
            $avatarName = uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('image', $avatarName);
            $imagePath = url($imagePath); // Đường dẫn hoàn chỉnh của ảnh
        }

        // Lấy tin nhắn cuối cùng trong nhóm
        $lastMessage = DB::table('chatgroup_message')
            ->where('campaign_id', $group_id)
            ->orderBy('id', 'desc')
            ->first();

        $now = now();

        // Tạo tin nhắn mới
        $chatData = [
            'user_from' => $user_id,
            'campaign_id' => $group_id,
            'content' => $content,
            'image' => $imagePath,
            'created_at' => $now,
        ];

        // Kiểm tra thời gian chênh lệch và bỏ qua cập nhật thời gian nếu cần
        if ($lastMessage && Carbon::parse($lastMessage->created_at)->diffInMinutes($now) < 10) {
            // Nếu thời gian chênh lệch nhỏ hơn 10 phút, không cập nhật 'created_at'
            unset($chatData['created_at']);
        }

        // Chèn tin nhắn mới vào DB
        $chatId = DB::table('chatgroup_message')->insertGetId($chatData);

        // Lấy lại tin nhắn để trả về cho client
        $chat = DB::table('chatgroup_message')
            ->select('user_from', 'content', 'image', 'created_at')
            ->where('id', $chatId)
            ->orderBy('created_at', 'asc')
            ->first();

        // Truy vấn thông tin user
        $user = DB::table('user_information')
            ->select('name', 'image')
            ->where('userlogin_id', $user_id)
            ->first();

        // Gắn thông tin user vào tin nhắn
        $chatData = [
            'user_from' => $chat->user_from,
            'content' => $chat->content,
            'image' => $chat->image ? url($chat->image) : null,
            'created_at' => $chat->created_at ? Carbon::parse($chat->created_at)->format('d-m H:i') : null,
            'user_name' => $user ? $user->name : 'Unknown',
            'user_image' => $user && $user->image ? url($user->image) : null
        ];

        // Truy vấn tất cả user_id từ bảng volunteer nơi campaign_id = group_id
        $userIds = DB::table('volunteer')
            ->where('campaignid', $group_id)
            ->pluck('userid')
            ->toArray();

        // Gửi mảng user_id về client cùng với thông tin tin nhắn
        return response()->json(['chat' => $chatData, 'userIds' => $userIds, 'group_id' => $group_id]);
    }
}
