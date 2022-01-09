<?php

namespace App\Domain\UseCases\Stats;

use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\Stats\StatsRequestModel;

interface StatsInputPort {
    public function tickets(StatsRequestModel $dto): ViewModel;
}

