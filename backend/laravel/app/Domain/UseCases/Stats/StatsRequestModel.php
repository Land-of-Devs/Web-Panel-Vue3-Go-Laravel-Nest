<?php

namespace App\Domain\UseCases\Stats;

class StatsRequestModel
{
    public function __construct(
        private array $attributes,
    ) {
    }

    public function getFromDate(): string {
        return $this->attributes['from_date'];
    }
    
    public function getToDate(): string {
        return $this->attributes['to_date'];
    }

}

