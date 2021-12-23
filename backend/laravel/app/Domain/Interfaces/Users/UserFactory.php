<?php

namespace App\Domain\Interfaces\Users;

interface UserFactory
{
  public function make(array $attribs = []): UserEntity;
}
