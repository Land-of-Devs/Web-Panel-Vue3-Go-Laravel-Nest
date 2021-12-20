<?php

namespace App\Adapters\Presenters;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateOutputPort;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateResponseModel;
use App\Http\Resources\TwoStepCodeResource;

class TwoStepGeneratePresenter implements TwoStepGenerateOutputPort
{
  public function codeGenerated(TwoStepGenerateResponseModel $model) : ViewModel
  {
    return new JsonResourceViewModel(
      new TwoStepCodeResource($model->getCode())
    );
  }
}
