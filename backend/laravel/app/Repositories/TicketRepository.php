<?php

namespace App\Repositories;

use App\Interfaces\ApiTicketInterface;
use App\Models\Ticket;

class TicketRepository implements ApiTicketInterface
{

    public function setStatus($tickets)
    {
        try {
            foreach ($tickets as $ticket) {
                $oldTicket = Ticket::find($ticket->id);
                if ($oldTicket) {
                    $oldTicket->status = $ticket->status;
                    $oldTicket->save();
                }
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function all()
    {
        return Ticket::with('user')->orderBy('created_at', 'desc')->paginate(9);
    }

    public function delete($ids)
    {
        try {
            foreach ($ids as $id) {
                $ticket = Ticket::find($id);
                if ($ticket) {
                    $ticket->delete();
                }
            }
            return true;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
