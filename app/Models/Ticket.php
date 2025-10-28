<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'user_id',
        'so_ghe',
        'trang_thai',
        'phuong_thuc_thanh_toan'
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // QUAN HỆ
    // =====================
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // =====================
    // TRẠNG THÁI VÉ
    // =====================
    public function getTrangThaiLabelAttribute()
    {
        return match ($this->trang_thai) {
            'pending'  => '<span class="badge bg-warning">Chờ thanh toán</span>',
            'paid'     => '<span class="badge bg-success">Đã thanh toán</span>',
            'canceled' => '<span class="badge bg-danger">Hủy</span>',
            default    => '<span class="badge bg-secondary">Không xác định</span>',
        };
    }

    // =====================
    // GHẾ
    // =====================

    // Ghế đã đặt cho vé này
    public function getBookedSeatsAttribute()
    {
        return $this->passengers->pluck('seat_number')->toArray();
    }

    // Ghế còn trống so với tổng ghế của xe
    // Hỗ trợ ký hiệu A1, B2… nếu trip->bus->so_ghe > 0
    public function getAvailableSeatsAttribute()
    {
        if(!$this->trip || !$this->trip->bus) return [];

        $totalSeats = $this->trip->bus->so_ghe;

        // Sinh ký hiệu ghế
        $rows = ['A','B','C','D','E','F']; // tối đa 6 hàng
        $cols = range(1, ceil($totalSeats / count($rows)));
        $allSeats = [];

        foreach($rows as $r){
            foreach($cols as $c){
                $seat = $r.$c;
                $allSeats[] = $seat;
                if(count($allSeats) >= $totalSeats) break;
            }
        }

        return array_diff($allSeats, $this->booked_seats);
    }

    // =====================
    // SCOPES
    // =====================
    public function scopePaid($query)
    {
        return $query->where('trang_thai', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('trang_thai', 'pending');
    }

    public function scopeCanceled($query)
    {
        return $query->where('trang_thai', 'canceled');
    }
}
