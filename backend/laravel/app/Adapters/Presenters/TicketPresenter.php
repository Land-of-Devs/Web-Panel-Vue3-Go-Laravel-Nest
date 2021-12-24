<?php

namespace App\Adapters\Presenters;

use App\Adapters\ViewModels\JsonResourceViewModel;
use App\Domain\Interfaces\Tickets\TicketEntity;
use App\Domain\Interfaces\ViewModel;
use App\Domain\UseCases\Tickets\TicketOutputPort;
use App\Http\Resources\ErrorResource;
use App\Http\Resources\SuccessResource;
use App\Http\Resources\ListResource;
use App\Http\Resources\Tickets\TicketResource;


class TicketPresenter implements TicketOutputPort
{
    public function listTickets(object $list): ViewModel
    {
        return new JsonResourceViewModel(
            new ListResource($list)
        );
    }

    public function ticket(TicketEntity $ticket): ViewModel
    {
        return new JsonResourceViewModel(
            new TicketResource($ticket)
        );
    }

    public function success(string $msg, int $code, object $result): ViewModel
    {
        return new JsonResourceViewModel(
            new SuccessResource($msg, $code, $result)
        );
    }

    public function fail(string $msg, int $code): ViewModel
    {
        return new JsonResourceViewModel(
            new ErrorResource($msg, $code)
        );
    }
}
