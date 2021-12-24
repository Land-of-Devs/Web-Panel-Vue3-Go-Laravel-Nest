<?php

namespace App\Http\Resources\Tickets;

use App\Domain\Interfaces\Tickets\TicketEntity;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function __construct(TicketEntity $ticket)
    {
        $this->resource = [
            'ticket' => $ticket 
        ];
    }
}
