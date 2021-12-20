<?php

namespace App\Domain\UseCases\TwoStepGenerate;

class TwoStepGenerateResponseModel
{
  public function __construct(
    private string $code,
  )
  { }

  public function getCode() : string
  {
    return $this->code;
  }
}