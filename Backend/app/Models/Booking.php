<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'driver_id',
        'vehicle_id',
        'pickup_location',
        'pickup_latitude',
        'pickup_longitude',
        'dropoff_location',
        'dropoff_latitude',
        'dropoff_longitude',
        'pickup_time',
        'dropoff_time',
        'fare',
        'status',
    ];

    /**
     * Get the driver associated with the booking.
     */
    public function driver()
    {
        return $this->belongsTo(Customer::class, 'driver_id');
    }

    public function passenger()
    {
        return $this->belongsTo(Customer::class, 'passenger_id');
    }

    /**
     * Get the vehicle associated with the booking.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the rating associated with this booking.
     */
    public function rating()
    {
        return $this->hasOne(DriverRating::class);
    }
}
