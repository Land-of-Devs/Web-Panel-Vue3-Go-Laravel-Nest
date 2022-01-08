<?php

namespace App\Repositories;


use App\Models\Ticket;
use App\Domain\Interfaces\Tickets\TicketEntity;
use App\Domain\Interfaces\Tickets\TicketRepository;
use App\Traits\RepositoryUtilsTrait;

class TicketDbRepository implements TicketRepository
{
    use RepositoryUtilsTrait;

    public function find(string $id): ?TicketEntity
    {
        return Ticket::with('user')->find($id);
    }

    public function betweenDate(string $from_date, string $to_date): array
    {
        return Ticket::whereBetween('created_at', [$from_date, $to_date])->get()->toArray();
    }


    public function all(): ?object
    {
        $where = [
            array(
                'key' => 'status',
                'exp' => '=',
                'value' => request('status')
            ),
            array(
                'key' => 'type',
                'exp' => '=',
                'value' => request('type')
            )
        ];
        return Ticket::with('user')
        ->where(self::cleanWhere($where))
        ->orderBy('created_at', 'desc')
        ->paginate(10);
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
