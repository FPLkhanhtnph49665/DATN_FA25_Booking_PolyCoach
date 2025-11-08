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
        'gio_khoi_hanh'  => 'string', // ✅ Đổi từ 'datetime' thành 'string'
        'ngay_den'       => 'date',
        'gio_den'        => 'string', // ✅ Đổi từ 'datetime' thành 'string'
        'gia_ve'         => 'decimal:0',
        'trang_thai'     => 'integer',
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

    protected static function booted()
    {
        static::creating(function ($trip) {
            $lastTrip = Trip::latest('id')->first();
            $number = $lastTrip ? $lastTrip->id + 1 : 1;
            $trip->ma_chuyen = 'TRIP_' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ✅ Accessor hiển thị giờ khởi hành (chỉ HH:MM)
    public function getGioKhoiHanhFormattedAttribute()
    {
        if (!$this->gio_khoi_hanh) {
            return '-';
        }
        
        // Nếu là string dạng HH:MM:SS, chỉ lấy HH:MM
        if (is_string($this->gio_khoi_hanh)) {
            return substr($this->gio_khoi_hanh, 0, 5);
        }
        
        return $this->gio_khoi_hanh;
    }

    // ✅ Accessor hiển thị giờ đến (chỉ HH:MM)
    public function getGioDenFormattedAttribute()
    {
        if (!$this->gio_den) {
            return '-';
        }
        
        // Nếu là string dạng HH:MM:SS, chỉ lấy HH:MM
        if (is_string($this->gio_den)) {
            return substr($this->gio_den, 0, 5);
        }
        
        return $this->gio_den;
    }

    // Lấy ghế đã đặt dạng array
    public function getBookedSeatsAttribute()
    {
        return $this->tickets()->pluck('so_ghe')->toArray();
    }

    // ✅ Ghế còn trống dạng ký hiệu A1, A2…
    public function getAvailableSeatsAttribute()
    {
        $bus = $this->bus;

        // Kiểm tra kỹ dữ liệu trước khi xử lý
        if (!$bus || !is_numeric($bus->so_ghe) || $bus->so_ghe <= 0 || $bus->so_ghe > 100) {
            return [];
        }

        $rows = ['A', 'B', 'C', 'D'];
        $cols = range(1, ceil($bus->so_ghe / count($rows)));

        $allSeats = [];
        $count = 0;

        foreach ($rows as $r) {
            foreach ($cols as $c) {
                $seat = $r . $c;
                $allSeats[] = $seat;
                $count++;
                if ($count >= $bus->so_ghe) break 2;
            }
        }

        return array_diff($allSeats, $this->booked_seats ?? []);
    }
}