<?php

namespace App\Domain\UseCases\TwoStepGenerate;

use App\Domain\Interfaces\ViewModel;
use RobThree\Auth\TwoFactorAuth;

class TwoStepGenerateInteractor implements TwoStepGenerateInputPort
{
  public function __construct(
    private TwoStepGenerateOutputPort $output,
    private TwoFactorAuth $tfa,
  ) {
  }

  public function generateCode(): ViewModel
  {
    return $this->output->codeGenerated($this->tfa->createSecret());
  }
}
