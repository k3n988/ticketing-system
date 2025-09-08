<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_name',      // e.g., RIFTWALKERS
        'order_number',    // auto-generated unique order code
        'customer_name',
        'email',
        'quantity',
        'status',
        'price',
        'university',      // optional
        'venue',           // optional
        'date',            // optional event date
        'time',            // optional event time
    ];

    // Example relationship if you have payments
    // public function payments()
    // {
    //     return $this->hasMany(Payment::class);
    // }
}
