<?php

namespace App\Repositories;


use App\Models\Ticket;
use App\Domain\Interfaces\Tickets\TicketEntity;
use App\Domain\Interfaces\Tickets\TicketRepository;

class TicketDbRepository implements TicketRepository
{

    public function find(string $id): ?TicketEntity
    {
        return Ticket::with('user')->find($id);
    }

    public function betweenDate(string $from_date, string $to_date): array {
        return Ticket::whereBetween('created_at',[$from_date, $to_date])->get()->toArray();
    }

    
    public function all(): ?object
    {
        return Ticket::with('user')->orderBy('created_at', 'desc')->paginate(9);
    }
    
    public function status(array $ids, string $status): ?object
    {
        try {
            $result = new \stdClass();
            $result->count = 0;
            $result->keys = [];
            $result->result = false;
            foreach ($ids as $id) {
                $ticket = $this->find($id);
                if ($ticket) {
                    $ticket->setStatus($status);
                    $ticket->saveTicket();
                    $result->count++;
                    array_push($result->keys, $id);
                }
            }
            $result->result = true;
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function delete($ids): ?object
    {
        try {
            $result = new \stdClass();
            $result->count = 0;
            $result->keys = [];
            $result->result = false;
            foreach ($ids as $id) {
                $ticket = $this->find($id);
                if ($ticket) {
                    $ticket->deleteTicket();
                    $result->count++;
                    array_push($result->keys, $id);
                }
            }
            $result->result = true;
            return $result;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
