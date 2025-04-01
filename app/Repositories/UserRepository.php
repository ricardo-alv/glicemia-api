<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        protected User $entity
    ) {}

    public function createNewUser(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->entity->create($data);
        $token = $user->createToken('token_user')->plainTextToken;
        $user->token = $token;
        return $user;
    }
}
