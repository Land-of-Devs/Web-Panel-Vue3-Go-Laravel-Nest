<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Interfaces\Tickets\TicketEntity;

class Ticket extends Model implements TicketEntity
{
    use HasFactory;

    protected $table = 'tickets';
    protected $hidden = ['creator'];

    //------[ RELATIONSHIPS ]------\\
    public function user()
    {
        return $this->belongsTo(User::class, 'creator');
    }

    //------[ SETTER ]------\\
    public function setStatus(string $status): void{
        $this->attributes['status'] = $status;
    }

    //------[ DB FUNCTIONS ]------\\
    public function saveTicket(): void{
        if (!$this->save()){
            throw new \Exception("Couldn't save the Ticket");
        }
    }

    public function deleteTicket(): void{
        if(!$this->delete()){
            throw new \Exception("Couldn't save Product");
        }
    }
}
