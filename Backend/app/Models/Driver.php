<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Driver extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'status',
        'vehicle_number',
        'model',
        'brand',
        'color',
        'latitude',
        'longitude',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
