<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Providers\UserService;
use App\Repositories\UserRepository;

class AdminUserController extends Controller
{
    protected $userService;

    public function users()
    {
        return view('admin.pages.users');
    }

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->all();
        dd($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
