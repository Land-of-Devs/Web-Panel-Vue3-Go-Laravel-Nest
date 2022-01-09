<?php

namespace App\Adapters\Presenters;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\Stats\StatsOutputPort;
use App\Http\Resources\ErrorResource;
use Illuminate\Http\Resources\Json\JsonResource;

class StatsPresenter implements StatsOutputPort {
    public function dataStats(array $data): ViewModel {
        return new JsonResourceViewModel(
            new JsonResource(
                array(
                    'stats' => $data
                )
            )
        );
    }

    public function diffDaysException(): ViewModel {
        return new JsonResourceViewModel(
            new ErrorResource('Too day difference')
        );
    }
}
