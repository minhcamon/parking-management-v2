<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyPasses extends Model
{
    protected $fillable = [
        'card_id',
        'ticket_type_id',
        'customer_name',
        'license_plate',
        'start_date',
        'end_date',
    ];

    public function card()
    {
        return $this->belongsTo(Cards::class, 'card_id');
    }

    public function ticket_type()
    {
        return $this->belongsTo(TicketTypes::class, 'ticket_type_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transactions::class, 'monthly_pass_id');
    }
}
