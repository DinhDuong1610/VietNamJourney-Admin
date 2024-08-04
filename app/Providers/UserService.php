<?php

namespace App\Providers;

use App\Models\User;


class UserService extends BaseCrudService {

    public function __construct(User $user)
    {
        parent::__construct($user);
    }


    public function all()
    {
        return $this->model->where('is_active', 1)->get();
    }

}
