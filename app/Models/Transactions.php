<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    protected $fillable = [
        'monthly_pass_id',
        'amount',
        'payment_time',
        'staff_id',
    ];

    public function monthly_pass()
    {
        return $this->belongsTo(MonthlyPasses::class, 'monthly_pass_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
