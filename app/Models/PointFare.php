<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointFare extends Model
{
    protected $fillable = ['route_id', 'pickup_point_id', 'dropoff_point_id', 'price'];

    // Quan hệ để lấy thông tin tên điểm nếu cần
    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class);
    }

    public function dropoffPoint()
    {
        return $this->belongsTo(DropoffPoint::class);
    }
    // Liên kết với Tuyến đường
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
