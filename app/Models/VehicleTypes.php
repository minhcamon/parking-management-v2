<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleTypes extends Model
{
    protected $fillable = [
        'name',
        'total_slots',
    ];

    public function ticket_types()
    {
        return $this->hasMany(TicketTypes::class, 'vehicle_type_id');
    }

    public function parking_sessions()
    {
        return $this->hasManyThrough(ParkingSessions::class, TicketTypes::class, 'vehicle_type_id', 'ticket_type_id');
    }
}
