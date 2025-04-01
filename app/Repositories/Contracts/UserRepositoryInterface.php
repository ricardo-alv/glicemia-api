<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function createNewUser(array $data);
}
