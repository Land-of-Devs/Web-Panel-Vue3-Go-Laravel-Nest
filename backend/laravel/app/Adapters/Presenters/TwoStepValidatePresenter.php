<?php

namespace App\Adapters\Presenters;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateOutputPort;
use App\Http\Resources\TwoStepValidationResource;

class TwoStepValidatePresenter implements TwoStepValidateOutputPort
{
  public function codeValid() : ViewModel
  {
    return new JsonResourceViewModel(
      new TwoStepValidationResource(true)
    );
  }

  public function codeInvalid() : ViewModel
  {
    return new JsonResourceViewModel(
      new TwoStepValidationResource(false)
    );
  }
}
