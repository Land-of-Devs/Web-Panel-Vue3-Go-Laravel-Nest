<?php

namespace App\Domain\UseCases\Tickets;

use App\Domain\Interfaces\ViewModel;

interface TicketInputPort
{
    public function all(): ViewModel;
    public function show(string $id): ViewModel;
    public function delete(array $ids): ViewModel;
    public function status(array $ids, string $status): ViewModel;
}
