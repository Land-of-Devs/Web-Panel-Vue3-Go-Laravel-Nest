<?php

namespace App\Factories;

use App\Domain\Interfaces\Tickets\TicketEntity;
use App\Domain\Interfaces\Tickets\TicketFactory;
use App\Models\Ticket;

class TicketModelFactory implements TicketFactory
{
  public function make(array $attribs = []): TicketEntity
  {
    return new Ticket($attribs);
  }
}
