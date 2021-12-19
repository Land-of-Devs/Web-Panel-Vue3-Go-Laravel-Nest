<?php

namespace App\Domain\UseCases\TwoStepValidate;

class TwoStepValidateRequestModel
{
  public function __construct(
    private array $attributes,
  ) {
  }

  public function getUuid(): string
  {
    return $this->attributes['uuid'];
  }

  public function getCode(): string
  {
    return $this->attributes['code'];
  }
}
