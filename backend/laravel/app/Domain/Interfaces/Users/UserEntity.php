<?php

namespace App\Domain\Interfaces\Users;

interface UserEntity
{
    public function getUuid(): ?string;
    public function getRole(): int;
    public function getName(): ?string;
    public function getEmail(): ?string;
    public function getTwoStepSecret(): ?string;
}
