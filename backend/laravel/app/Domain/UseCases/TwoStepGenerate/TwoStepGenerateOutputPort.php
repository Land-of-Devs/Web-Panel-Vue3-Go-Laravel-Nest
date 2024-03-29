<?php

namespace App\Domain\UseCases\TwoStepGenerate;

use App\Domain\Interfaces\ViewModel;

interface TwoStepGenerateOutputPort
{
  public function codeGenerated(string $code) : ViewModel;
}
