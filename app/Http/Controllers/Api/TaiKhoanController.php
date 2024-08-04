<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class TaiKhoanController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Username và Password không hợp lệ'], 400);
        }

        $username = $request->input('username');
        $password = $request->input('password');

        try {
            // Check login information
            $user = DB::table('user')
                ->where('Username', $username)
                ->first();

            if ($user && Hash::check($password, $user->Password)) {
                // Set User_ID and Username cookies
                return response()->json([
                    'success' => 'Đăng nhập thành công',
                    'user' => ['UserLogin_ID' => $user->Id, 'Username' => $user->Username]
                ])->cookie('User_ID', $user->Id, 43200) // 30 days
                    ->cookie('UserName', $user->Username, 43200); // 30 days
            } else {
                return response()->json(['error' => 'Email hoặc mật khẩu sai !'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Kết nối đến cơ sở dữ liệu thất bại: ' . $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'username' => 'required|string|unique:user,Username',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $email = htmlspecialchars(strip_tags($request->input('email')));
        $username = htmlspecialchars(strip_tags($request->input('username')));
        $password = htmlspecialchars(strip_tags($request->input('password')));

        // Default image path
        $imagePath = "public/images/clone.jpg";

        try {
            // Insert into user table
            $userId = DB::table('user')->insertGetId([
                'Username' => $username,
                'Password' => Hash::make($password),
            ]);

            // Insert into user_information table
            DB::table('user_information')->insert([
                'UserLogin_ID' => $userId,
                'Username' => $username,
                // 'Image' => $imagePath,
                'Image' => 'image/clone.jpg',
                'Name' => 'TÀI KHOẢN',
                'Email' => $email,
            ]);

            return response()->json(['success' => true, 'user' => ['UserLogin_ID' => $userId, 'Username' => $username]]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        }
    }
}
