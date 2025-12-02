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
        return $this->belongsTo(Route::class);
    }
}
