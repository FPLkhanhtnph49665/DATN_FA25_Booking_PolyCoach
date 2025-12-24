<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PickupPoint extends Model
{
    // use SoftDeletes;

    protected $fillable = [
        'route_id',
        'city_id',
        'name',
        'address',
        'order',
    ];

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
