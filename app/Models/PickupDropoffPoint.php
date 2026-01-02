<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickupDropoffPoint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city_id',
        'route_id',
        'name',
        'address',
        'time',
        'type',
        'active',
    ];

    // Quan hệ với City
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // Quan hệ với Route
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
    // Quan hệ với PointFare
    public function pickupFares()
    {
        return $this->hasMany(PointFare::class, 'pickup_point_id');
    }
    public function dropoffFares()
    {
        return $this->hasMany(PointFare::class, 'dropoff_point_id');
    }
    public function allFares()
    {
        return PointFare::where('pickup_point_id', $this->id)
            ->orWhere('dropoff_point_id', $this->id);
    }

}
