<?php

namespace App\Domain\Interfaces\Tickets;

interface TicketEntity
{
    public function setStatus(string $status): void;
    public function saveTicket(): void;
    public function deleteTicket(): void;
}
