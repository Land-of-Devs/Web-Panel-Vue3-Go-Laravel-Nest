<?php

namespace App\Factories;

use App\Domain\Interfaces\UserEntity;
use App\Domain\Interfaces\UserFactory;
use App\Models\User;

class UserModelFactory implements UserFactory
{
  public function make(array $attribs = []): UserEntity
  {
    return new User($attribs);
  }
}
