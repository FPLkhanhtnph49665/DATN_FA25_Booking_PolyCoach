<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointFare extends Model
{
    protected $fillable = [
        'route_id',
        'pickup_point_id',
        'dropoff_point_id',
        'price',
        'active',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
    public function pickupPoint()
    {
        return $this->belongsTo(PickupDropoffPoint::class, 'pickup_point_id');
    }
    public function dropoffPoint()
    {
        return $this->belongsTo(PickupDropoffPoint::class, 'dropoff_point_id');
    }
}
