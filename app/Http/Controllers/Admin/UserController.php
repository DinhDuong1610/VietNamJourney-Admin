<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Services\UserService;

class UserController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function userList()
    {
        $users = $this->userService->getUserList();

        return view('admin.pages.user.index', [
            'users' => $users
        ]);
    }
    
}
