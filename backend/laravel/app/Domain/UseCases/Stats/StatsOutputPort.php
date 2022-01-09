<?php

namespace App\Domain\UseCases\Stats;

use App\Domain\Interfaces\ViewModel;

interface StatsOutputPort {
    public function dataStats(array $data) : ViewModel;
    public function diffDaysException() : ViewModel;
}