<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'trip_id',
        'user_id',
        'booking_id',
        'point_fare_id',
        'price',
        'status',
        'payment_method',
        'seat_code',
        'checked_at',
        'checked_by',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    // Người đặt vé
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Người kiểm vé
    public function checker()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }
    public function passengers()
    {
        return $this->hasMany(Booking::class, 'trip_id');
    }
    public function pointFare()
    {
        return $this->belongsTo(PointFare::class, 'point_fare_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (!$ticket->code) {
                $ticket->code = 'datn-' . strtoupper(uniqid());
            }
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '<span class="badge bg-warning">Đang chờ</span>',
            'paid'      => '<span class="badge bg-success">Đã thanh toán</span>',
            'cancelled' => '<span class="badge bg-danger">Đã hủy</span>',
            default     => '<span class="badge bg-secondary">Không rõ</span>',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'  => 'Tiền mặt',
            'momo'  => 'Momo',
            'bank'  => 'Chuyển khoản',
            default => 'Không rõ',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | SEAT HANDLING
    |--------------------------------------------------------------------------
    */

    // Lấy danh sách ghế đã đặt của chuyến xe (dựa trên bảng tickets)
    public static function getBookedSeats(int $tripId): array
    {
        return Ticket::where('trip_id', $tripId)
            ->whereNull('deleted_at')
            ->pluck('seat_code')
            ->toArray();
    }

    // Tính ghế còn trống trong chuyến
    public static function getAvailableSeats($trip)
    {
        if (!$trip || !$trip->bus) {
            return [];
        }

        $totalSeats = $trip->bus->seat_count;

        // Danh sách ghế A1 → A2 → ... dựa trên số ghế
        $rows = range('A', 'Z');
        $cols = range(1, ceil($totalSeats / 26));

        $allSeats = [];
        foreach ($rows as $r) {
            foreach ($cols as $c) {
                $allSeats[] = $r . $c;
                if (count($allSeats) >= $totalSeats) break 2;
            }
        }

        $booked = self::getBookedSeats($trip->id);

        return array_values(array_diff($allSeats, $booked));
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }
}
