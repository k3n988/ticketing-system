<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['ticket_id','amount','method'];
    public function ticket() {
        return $this->belongsTo(Ticket::class);
    }
}

