<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_code',
        'customer_id',
        'trip_id',
        'tong_tien',
        'trang_thai', // pending | confirmed | canceled
    ];

    protected static function booted()
    {
        static::creating(function ($booking) {
            $last = Booking::latest('id')->first();
            $nextId = $last ? $last->id + 1 : 1;
            $booking->booking_code = 'BOOK_' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    // Booking thuộc về một khách hàng (user có role = customer)
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Booking thuộc về một chuyến đi
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Booking có nhiều hành khách
    public function passengers()
    {
        return $this->hasMany(Passenger::class);
    }

    // Booking có nhiều vé
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Booking có thể có nhiều thanh toán
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // -------------------------------
    // 🧮 ACCESSORS / HELPERS
    // -------------------------------
    public function getFormattedTotalAttribute()
    {
        return number_format($this->tong_tien, 0, ',', '.') . ' ₫';
    }

    public function isPending()
    {
        return $this->trang_thai === 'pending';
    }

    public function isConfirmed()
    {
        return $this->trang_thai === 'confirmed';
    }

    public function isCanceled()
    {
        return $this->trang_thai === 'canceled';
    }
}
