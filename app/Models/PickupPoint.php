<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PickupPoint extends Model
{
    protected $fillable = [
        'route_id',
        'ten_diem_don',
        'dia_chi',
        'order',
    ];
    // Quan hệ: Một điểm đón thuộc về một Route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
