<?php

namespace App\Models;

use App\Mail\PassengerCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;

class Passenger extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
