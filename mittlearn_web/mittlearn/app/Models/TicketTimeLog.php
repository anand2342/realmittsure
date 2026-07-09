<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTimeLog extends Model
{
    protected $fillable = ['ticket_id', 'user_id', 'hours', 'description', 'logged_date'];

    protected $casts = ['logged_date' => 'date', 'hours' => 'decimal:2'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}