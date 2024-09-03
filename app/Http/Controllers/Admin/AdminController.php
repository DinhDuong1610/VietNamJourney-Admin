<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\UserService;

class AdminController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function login()
    {
        return view('admin.pages.auth.login');
    }

    public function loginPost(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:6'
        ]);
        $username = $request->username;
        $password = $request->password;
        $user = $this->userService->checkLogin($username, $password);
        if ($user) {
            auth()->login($user);
            return redirect()->route('admin.dashboard');
        } elseif ($user == false) {
            return redirect()->route('admin.auth.login')->with('error', 'Email or password is incorrect');
        }
    }

    public function register()
    {
        return view('admin.pages.auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'email'=> 'required',
            'username' => 'required',
            'password' => 'required|min:6'
        ]);
        $user = $this->userService->register($request);
        if ($user) {
            auth()->login($user);
            return redirect()->route('admin.auth.login')->with('success', 'Register success');
        } else {
            return redirect()->route('admin.auth.register')->with('error', 'Register failed');
        }
    }
}
