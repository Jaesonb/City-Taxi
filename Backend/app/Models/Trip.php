<?php

namespace App\Models;

use App\Mail\TripCreated;
use App\Mail\TripStatusUpdated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Trip extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';

    protected $fillable = [
        'passenger_id',
        'driver_id',
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

    protected $dates = [
        'pickup_time',
        // Add other date fields here if necessary
    ];

    private $fareRate = 100;

    /**
     * Get the driver associated with the booking.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the passenger associated with the booking.
     */
    public function passenger()
    {
        return $this->belongsTo(Passenger::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Calculate the distance between pickup and dropoff locations.
     */
    public function calculateDistance()
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $latFrom = deg2rad($this->pickup_latitude);
        $lonFrom = deg2rad($this->pickup_longitude);
        $latTo = deg2rad($this->dropoff_latitude);
        $lonTo = deg2rad($this->dropoff_longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // returns distance in kilometers
    }

    public function calculateFare()
    {
        $distance = $this->calculateDistance();
        return $distance * $this->fareRate; // Returns fare based on the rate per kilometer
    }

    /**
     * Boot method for model events.
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new trip, calculate and set the fare
        static::creating(function ($trip) {
            $trip->fare = $trip->calculateFare();
        });

        // After the trip is created, send the email with the trip details
        static::created(function ($trip) {
            Mail::to($trip->driver->email)->send(new TripCreated($trip));
        });

        // When updating an existing trip, calculate and set the fare
        static::updating(function ($trip) {
            $trip->fare = $trip->calculateFare();
        });

        // After updating the trip, notify the passenger
        static::updated(function ($trip) {
            if ($trip->isDirty('status')) { // Only send email if status has changed
                Mail::to($trip->passenger->email)->send(new TripStatusUpdated($trip));
            }
        });
    }
}
