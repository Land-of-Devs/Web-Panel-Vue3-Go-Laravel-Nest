<?php

namespace App\Domain\Interfaces\Tickets;

use App\Domain\Interfaces\Tickets\TicketEntity;

interface TicketRepository
{
    public function all(): ?object;
    public function find(string $id): ?TicketEntity;
    public function status(array $ids, string $status): ?object;
    public function delete(array $ids): ?object;
    public function betweenDate(string $from_date, string $to_date): array;
}
