<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'driver_id',
        'rating',
        'comment',
    ];

    public function driver()
    {
        return $this->belongsTo(Customer::class, 'driver_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
