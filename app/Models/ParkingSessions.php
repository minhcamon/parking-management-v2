<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkingSessions extends Model
{
    protected $fillable = [
        'card_id',
        'ticket_type_id',
        'license_plate',
        'check_in_time',
        'staff_id_in',
        'status',
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime',
    ];

    public function card()
    {
        return $this->belongsTo(Cards::class, 'card_id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketTypes::class, 'ticket_type_id');
    }

    public function staff_in()
    {
        return $this->belongsTo(User::class, 'staff_id_in');
    }

    public function staff_out()
    {
        return $this->belongsTo(User::class, 'staff_id_out');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'session_id');
    }
}
