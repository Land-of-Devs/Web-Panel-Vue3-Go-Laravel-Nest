<?php

namespace App\Domain\UseCases\TwoStepGenerate;

use App\Domain\Interfaces\ViewModel;

interface TwoStepGenerateInputPort
{
  public function generateCode() : ViewModel;
}
