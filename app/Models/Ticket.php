<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'trip_id',
        'user_id',
        'so_ghe',
        'trang_thai',
        'phuong_thuc_thanh_toan',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // 🔗 QUAN HỆ
    // =====================
    public function trip()
    {
        return $this->belongsTo(Trip::class)->with(['bus', 'route']);
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
    // 💡 ACCESSORS
    // =====================
    public function getTrangThaiLabelAttribute(): string
    {
        return match ($this->trang_thai) {
            'pending'  => '<span class="badge bg-warning">Chờ thanh toán</span>',
            'paid'     => '<span class="badge bg-success">Đã thanh toán</span>',
            'canceled' => '<span class="badge bg-danger">Đã hủy</span>',
            default    => '<span class="badge bg-secondary">Không xác định</span>',
        };
    }

    public function getPhuongThucThanhToanLabelAttribute(): string
    {
        return match ($this->phuong_thuc_thanh_toan) {
            'cash' => 'Tiền mặt',
            'momo' => 'Momo',
            'bank' => 'Chuyển khoản',
            default => 'Không rõ',
        };
    }

    public function getBookedSeatsAttribute(): array
    {
        return $this->passengers->pluck('seat_number')->toArray();
    }

    public function getAvailableSeatsAttribute(): array
    {
        if (!$this->trip || !$this->trip->bus) return [];

        $totalSeats = $this->trip->bus->so_ghe;
        $booked = $this->booked_seats;

        // Sinh ký hiệu ghế tự động
        $rows = range('A', 'Z');
        $cols = range(1, ceil($totalSeats / count($rows)));

        $allSeats = [];
        foreach ($rows as $r) {
            foreach ($cols as $c) {
                $seat = $r . $c;
                $allSeats[] = $seat;
                if (count($allSeats) >= $totalSeats) break 2;
            }
        }

        return array_values(array_diff($allSeats, $booked));
    }

    // =====================
    // 🔍 SCOPES
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

    // Lọc theo người dùng
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Lọc theo chuyến
    public function scopeByTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }
}
