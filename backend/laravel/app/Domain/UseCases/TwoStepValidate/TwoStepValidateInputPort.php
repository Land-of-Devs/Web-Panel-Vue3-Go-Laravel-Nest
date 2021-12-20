<?php

namespace App\Domain\UseCases\TwoStepValidate;

use App\Domain\Interfaces\ViewModel;

interface TwoStepValidateInputPort
{
  public function verifyCode(TwoStepValidateRequestModel $data): ViewModel;
}