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
        $user = $this->user->where('username', $username)->first();
        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        return false;
    }

    public function register($request)
    {
        $userCreate = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
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
