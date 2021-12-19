<?php

namespace App\Domain\Interfaces;

interface UserEntity
{
  public function getUuid() : ?string;

  public function getRole() : int;

  public function getName() : ?string;
  public function setName(string $name);

  public function getEmail() : ?string;
  public function setEmail(string $email);

  // public function setPassword(string $password);

  public function getTwoStepSecret() : ?string;
  // public function setTwoStepSecret(string $secret);
}
