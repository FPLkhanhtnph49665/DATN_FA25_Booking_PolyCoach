<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',                // FK tới users.id
        'trip_id',                // FK tới trips.id
        'ngay_dat',
        'tong_tien',
        'trang_thai',             // 'Đang chờ' | 'Đã xác nhận' | 'Đã thanh toán' | 'Đã hủy'
        'phuong_thuc_thanh_toan', // cash / momo / bank / ...
    ];

    protected $casts = [
        'ngay_dat' => 'datetime',
    ];

    // Nếu chưa set ngày đặt thì mặc định now()
    protected static function booted()
    {
        static::creating(function ($booking) {
            if (!$booking->ngay_dat) {
                $booking->ngay_dat = now();
            }
        });
    }

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    // Booking thuộc về một user (customer)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Có thể dùng alias customer() nếu thích
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Booking thuộc về một chuyến đi
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // (Tạm thời KHÔNG khai báo passengers/tickets/payments
    // vì schema chưa có booking_id ở các bảng đó)

    // -------------------------------
    // 🧮 ACCESSORS / HELPERS
    // -------------------------------

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->tong_tien, 0, ',', '.') . ' ₫';
    }

    public function isPending(): bool
    {
        return $this->trang_thai === 'Đang chờ';
    }

    public function isConfirmed(): bool
    {
        return in_array($this->trang_thai, ['Đã xác nhận', 'Đã thanh toán'], true);
    }

    public function isPaid(): bool
    {
        return $this->trang_thai === 'Đã thanh toán';
    }

    public function isCanceled(): bool
    {
        return $this->trang_thai === 'Đã hủy';
    }
    
}
