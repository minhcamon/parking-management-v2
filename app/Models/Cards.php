<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    protected $fillable = [
        'rfid_code',
        'status',
    ];

    public function monthly_passes()
    {
        return $this->hasMany(MonthlyPasses::class, 'card_id');
    }

    public function parking_sessions()
    {
        return $this->hasMany(ParkingSessions::class, 'card_id');
    }
}
