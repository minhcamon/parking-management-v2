<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketTypes extends Model
{
    protected $fillable = [
        'name',
        'price',
        'is_active',
        'vehicle_type_id',
        'type',
    ];

    public function vehicle_type()
    {
        return $this->belongsTo(VehicleTypes::class, 'vehicle_type_id');
    }

    public function monthly_passes()
    {
        return $this->hasMany(MonthlyPasses::class, 'ticket_type_id');
    }

    public function parking_sessions()
    {
        return $this->hasMany(ParkingSessions::class, 'ticket_type_id');
    }
}
