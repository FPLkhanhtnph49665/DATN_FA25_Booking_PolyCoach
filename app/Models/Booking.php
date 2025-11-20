<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'trip_id',
        'booking_datetime',
        'total_amount',
        'status',
        'payment_method',
    ];

    protected $casts = [
        'booking_datetime' => 'datetime',
        'total_amount' => 'integer',
    ];

    // -------------------------------
    // RELATIONSHIPS
    // -------------------------------
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // -------------------------------
    // ACCESSORS / HELPERS
    // -------------------------------
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . ' ₫';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Đang chờ</span>',
            'confirmed' => '<span class="badge bg-primary">Đã xác nhận</span>',
            'paid' => '<span class="badge bg-success">Đã thanh toán</span>',
            'cancelled' => '<span class="badge bg-danger">Đã hủy</span>',
            default => '<span class="badge bg-dark">Không xác định</span>',
        };
    }

    // -------------------------------
    // SCOPES
    // -------------------------------
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    public function scopeUserId($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeTripId($query, int $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    // -------------------------------
    // Booted: tự động set ngày đặt nếu null
    // -------------------------------
    protected static function booted()
    {
        static::creating(function ($booking) {
            if (!$booking->booking_datetime) {
                $booking->booking_datetime = now();
            }
        });
    }
}
