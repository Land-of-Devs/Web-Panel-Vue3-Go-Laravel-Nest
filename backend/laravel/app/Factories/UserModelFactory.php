<?php

namespace App\Factories;

use App\Domain\Interfaces\Users\UserEntity;
use App\Domain\Interfaces\Users\UserFactory;
use App\Models\User;

class UserModelFactory implements UserFactory
{
  public function make(array $attribs = []): UserEntity
  {
    return new User($attribs);
  }
}
