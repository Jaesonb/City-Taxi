<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'amount',
        'payment_method',
        'payment_status',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
