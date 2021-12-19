<?php

namespace App\Domain\Interfaces;

interface UserFactory
{
  public function make(array $attribs = []): UserEntity;
}
