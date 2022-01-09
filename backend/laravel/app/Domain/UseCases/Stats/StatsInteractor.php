<?php

namespace App\Domain\UseCases\Stats;

use App\Domain\Interfaces\Tickets\TicketRepository;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\Stats\StatsOutputPort;
use App\Domain\UseCases\Stats\StatsRequestModel;
use App\Traits\RepositoryUtilsTrait;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class StatsInteractor implements StatsInputPort {
    use RepositoryUtilsTrait;

    public function __construct(
        private StatsOutputPort $output,
        private TicketRepository $ticket
    ) {

    }

    public function tickets(StatsRequestModel $dto): ViewModel {

        $fromTimeU = strtotime($dto->getFromDate());
        $toTimeU = strtotime($dto->getToDate());

        $dayseconds = 86400;
        $diffdays = abs(ceil(($toTimeU - $fromTimeU) / $dayseconds));

        if ($diffdays > 10000) {
            return $this->output->diffDaysException();
        }

        $products = $this->ticket->betweenDate($dto->getFromDate(), $dto->getToDate());
        
        $arr = array_fill(0, $diffdays, 0);

        foreach ($products as &$product) {
            $arr[intval(strtotime($product["created_at"])/$dayseconds)-intval($fromTimeU/$dayseconds)]++;
        }

        return $this->output->dataStats($arr);

    }
}