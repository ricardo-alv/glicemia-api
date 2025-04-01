<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreUser;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;


class RegisterController extends Controller
{
    public function __construct(
        protected  UserService $userService
    ) {}

    public function store(StoreUser $request)
    {
        $user = $this->userService->createNewUser($request->validated());
        return new UserResource($user);
    }
}
