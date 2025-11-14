<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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
        'trang_thai',
        'ma_chuyen'
    ];

    protected $casts = [
        'ngay_khoi_hanh' => 'date:Y-m-d',
        'gio_khoi_hanh'  => 'string',
        'ngay_den'       => 'date:Y-m-d',
        'gio_den'        => 'string',
    ];

    // ========== QUAN HỆ ==========

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id', 'id');
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class, 'bus_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function passengers()
    {
        // Trip(id) -> Ticket(trip_id) -> Passenger(ticket_id)
        return $this->hasManyThrough(Passenger::class, Ticket::class);
    }

    // ========== SCOPES ==========

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('trang_thai', 1);
    }

    // ========== BOOT ==========

    protected static function booted()
    {
        static::creating(function ($trip) {
            // Lấy id lớn nhất, kể cả đã soft delete
            $lastId = Trip::withTrashed()->max('id') ?? 0;
            $number = $lastId + 1;

            $trip->ma_chuyen = 'TRIP_' . str_pad($number, 5, '0', STR_PAD_LEFT);
        });
    }

    // ========== ACCESSORS GIỜ ==========

    public function getGioKhoiHanhFormattedAttribute(): ?string
    {
        return $this->gio_khoi_hanh
            ? date('H:i', strtotime($this->gio_khoi_hanh))
            : null;
    }

    public function getGioDenFormattedAttribute(): ?string
    {
        return $this->gio_den
            ? date('H:i', strtotime($this->gio_den))
            : null;
    }

    // ========== GHẾ ==========

    // GHẾ ĐÃ ĐẶT (mã ghế) -> ['A01', 'A02', ...]
    public function getBookedSeatsAttribute(): array
    {
        return $this->passengers->pluck('seat_number')->toArray();
    }

    // GHẾ CÒN TRỐNG (mã ghế)
    public function getAvailableSeatsAttribute(): array
    {
        $bus = $this->bus;

        if (!$bus || !is_numeric($bus->so_ghe) || $bus->so_ghe <= 0 || $bus->so_ghe > 100) {
            return [];
        }

        // bạn đang giả định 4 hàng A,B,C,D
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

        // 🔥 Lưu ý: dùng booked_seats (snake_case), không phải bookedSeats
        $booked = $this->booked_seats ?? [];

        return array_values(array_diff($allSeats, $booked));
    }

    // Tổng số ghế của xe chạy chuyến này
    public function getTongSoGheAttribute(): int
    {
        return (int) ($this->bus->so_ghe ?? 0);
    }

    // Số ghế đã bán (theo passengers)
    public function getSoGheDaBanAttribute(): int
    {
        return count($this->booked_seats);
    }

    // Ghế trống (số lượng)
    public function getSoGheTrongAttribute(): int
    {
        return max(0, $this->tong_so_ghe - $this->so_ghe_da_ban);
    }
}
