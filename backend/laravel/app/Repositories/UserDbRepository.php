<?php

namespace App\Repositories;

use App\Domain\Interfaces\Users\UserEntity;
use App\Domain\Interfaces\Users\UserRepository;
use App\Models\User;
use App\Traits\RepositoryUtilsTrait;

class UserDbRepository implements UserRepository
{
  use RepositoryUtilsTrait;

  public function exists(UserEntity $user): bool
  {
    return User::where(self::cleanArray([
      'id' => $user->getUuid(),
      'username' => $user->getName(),
      'email' => $user->getEmail(),
    ]))->exists();
  }

  public function get(UserEntity $user) : ?UserEntity
  {
    return User::where(self::cleanArray([
      'id' => $user->getUuid(),
      'username' => $user->getName(),
      'email' => $user->getEmail(),
    ]))->first();;
  }
}
