<?php

namespace App\Domain\Interfaces\Users;

use App\Domain\Interfaces\Users\UserEntity;

interface UserRepository
{
    public function exists(UserEntity $user): bool;
    public function get(UserEntity $user) : ?UserEntity;
}
