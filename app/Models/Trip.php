<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'route_id',
        'bus_id',
        'ngay_khoi_hanh',
        'gio_khoi_hanh',
        'ngay_den',
        'gio_den',
        'gia_ve',
        'trang_thai'
    ];

    protected $casts = [
        'ngay_khoi_hanh' => 'date',
        'gio_khoi_hanh'  => 'datetime',
        'ngay_den'       => 'date',
        'gio_den'        => 'datetime',
    ];

    // Quan hệ tuyến
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    // Quan hệ xe
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // Quan hệ vé
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Quan hệ đánh giá
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Lấy tất cả hành khách qua vé
    public function passengers()
    {
        return $this->hasManyThrough(Passenger::class, Ticket::class);
    }

    // Scope chuyến đang hoạt động
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }

    // Accessor hiển thị giờ khởi hành H:i
    public function getGioKhoiHanhFormattedAttribute()
    {
        return $this->gio_khoi_hanh ? $this->gio_khoi_hanh->format('H:i') : null;
    }

    // Accessor hiển thị giờ đến H:i
    public function getGioDenFormattedAttribute()
    {
        return $this->gio_den ? $this->gio_den->format('H:i') : null;
    }

    // Lấy ghế đã đặt dạng array (nếu seat_number là integer)
    public function getBookedSeatsAttribute()
    {
        return $this->passengers->pluck('seat_number')->toArray();
    }

    // Ghế còn trống dạng ký hiệu A1, A2…
public function getAvailableSeatsAttribute() {
    if(!$this->bus) return [];
    $rows = ['A','B','C','D'];
    $cols = range(1, ceil($this->bus->so_ghe / count($rows)));
    $allSeats = [];
    foreach($rows as $r){
        foreach($cols as $c){
            $seat = $r.$c;
            $allSeats[] = $seat;
            if(count($allSeats) >= $this->bus->so_ghe) break;
        }
    }
    return array_diff($allSeats, $this->booked_seats);
}
}
