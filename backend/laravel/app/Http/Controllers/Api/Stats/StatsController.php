<?php

namespace App\Http\Controllers\Api\Stats;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\UseCases\Stats\StatsInputPort;
use App\Domain\UseCases\Stats\StatsRequestModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Stats\TicketStatsRequest;

class StatsController extends Controller
{
    public function __construct(
        private StatsInputPort $interactor
    ) {
    }

    public function getTicketStats(TicketStatsRequest $request)
    {
        $dto = new StatsRequestModel($request->validated());
        $viewModel = $this->interactor->tickets($dto);

        if ($viewModel instanceof JsonResourceViewModel) {
            return $viewModel->getResource();
        }

        return null;
    }
}
