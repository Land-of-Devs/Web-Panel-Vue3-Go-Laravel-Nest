<?php

namespace App\Http\Controllers\Api\Internal;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateInputPort;
use App\Domain\UseCases\TwoStepValidate\TwoStepValidateRequestModel;
use App\Http\Requests\TwoStepCodeValidateRequest;
use App\Http\Controllers\Controller;

/**
 * Verify the 2fa code for the given user.
 */
class TwoStepCodeValidateController extends Controller
{
    public function __construct(
        private TwoStepValidateInputPort $interactor,
    ) {
    }

    public function __invoke(TwoStepCodeValidateRequest $data)
    {
        $viewModel = $this->interactor->verifyCode(
            new TwoStepValidateRequestModel($data->validated())
        );

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }
}
