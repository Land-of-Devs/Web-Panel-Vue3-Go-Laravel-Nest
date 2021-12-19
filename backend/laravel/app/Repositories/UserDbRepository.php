<?php

namespace App\Repositories;

use App\Domain\Interfaces\UserEntity;
use App\Domain\Interfaces\UserRepository;
use App\Models\User;
use App\Traits\RepositoryUtilsTrait;

class UserDbRepository implements UserRepository
{
  use RepositoryUtilsTrait;

  public function exists(UserEntity $user): bool
  {
    return User::where(self::cleanWhere([
      'id' => $user->getUuid(),
      'username' => $user->getName(),
      'email' => $user->getEmail(),
    ]))->exists();
  }
  
  /** WARN: Not fully implemented */
  public function create(UserEntity $user, string $password) : UserEntity
  {
    return User::create([
      'username' => $user->getName(),
      'email' => $user->getEmail()
    ]);
  }

  public function get(UserEntity $user) : ?UserEntity
  {
    return User::where(self::cleanWhere([
      'id' => $user->getUuid(),
      'username' => $user->getName(),
      'email' => $user->getEmail(),
    ]))->first();;
  }
}
