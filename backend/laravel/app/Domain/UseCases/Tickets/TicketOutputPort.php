<?php

namespace App\Domain\UseCases\Tickets;

use App\Domain\Interfaces\Tickets\TicketEntity;
use App\Domain\Interfaces\ViewModel;

interface TicketOutputPort
{
    public function ticket(TicketEntity $ticket): ViewModel;
    public function listTickets(object $tickets): ViewModel;
    public function success(string $msg, int $code, object $result): ViewModel;
    public function fail(string $msg, int $code): ViewModel;
}
