<?php

namespace App\Domain\UseCases\TwoStepValidate;

use App\Domain\Interfaces\ViewModel;

interface TwoStepValidateOutputPort
{
  public function codeValid(): ViewModel;
  public function codeInvalid(): ViewModel;
}
