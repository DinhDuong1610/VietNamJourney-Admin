<?php

namespace App\Http\Services;

use App\Models\User;

class UserService extends BaseCrudService
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        parent::__construct($user);
    }

    public function checkLogin($username, $password)
    {
        $user = $this->user->where('Username', $username)->first();
        if ($user) {
            if (password_verify($password, $user->Password)) {
                return $user;
            }
        }
        return false;
    }

    public function register($request)
    {
        $userCreate = $this->user->create([
            'Username' => $request->username,
            'Password' => bcrypt($request->password),
            'is_admin' => 1,
        ]);
        
        return $userCreate;
    }

    public function getUserList()
    {
        return $this->user
            ->with('userInformation')
            ->orderBy('id')
            ->paginate(8);
    }
}
