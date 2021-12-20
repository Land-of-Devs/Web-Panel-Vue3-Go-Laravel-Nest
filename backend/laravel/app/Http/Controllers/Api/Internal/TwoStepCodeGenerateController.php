<?php

namespace App\Http\Controllers\Api\Internal;

use App\Adapters\Presenters\TwoStepGeneratePresenter;
use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\UseCases\TwoStepGenerate\TwoStepGenerateInputPort;
use App\Http\Controllers\Controller;

/**
 * Generate a 2fa secret. Only generates, doesn't save anything to the DB.
 */
class TwoStepCodeGenerateController extends Controller
{
  public function __construct(
    private TwoStepGenerateInputPort $interactor,
  ) {
  }

  public function __invoke()
  {
    $viewModel = $this->interactor->generateCode();

    if ($viewModel instanceof JsonResourceViewModel) {
      return $viewModel->getResource();
    }

    return null;
  }
}
