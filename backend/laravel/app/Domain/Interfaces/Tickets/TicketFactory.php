<?php

namespace App\Domain\Interfaces\Tickets;

interface TicketFactory
{
    public function make(array $attribs = []): TicketEntity;
}
