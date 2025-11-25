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
        'user_id',           // who booked the ticket
        'seat_number',        // number of seats in this ticket (1,2,3,...)
        'seat_code',
        'status',            // pending | paid | canceled
        'payment_method',    // cash | momo | bank
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // 🔗 RELATIONSHIPS
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
    // 💡 ACCESSORS / HELPERS
    // =====================

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'  => '<span class="badge bg-warning">Pending</span>',
            'paid'     => '<span class="badge bg-success">Paid</span>',
            'canceled' => '<span class="badge bg-danger">Canceled</span>',
            default    => '<span class="badge bg-secondary">Unknown</span>',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'cash'  => 'Cash',
            'momo'  => 'Momo',
            'bank'  => 'Bank Transfer',
            default => 'Unknown',
        };
    }

    // List of booked seat numbers for this ticket (assuming stored in passengers)
    public function getBookedSeatsAttribute(): array
    {
        return $this->passengers->pluck('seat_number')->toArray();
    }

    // Available seats for the trip
    public function getAvailableSeatsAttribute(): array
    {
        $trip = $this->trip;
        if (!$trip || !$trip->bus) return [];

        $bus = $trip->bus;
        $totalSeats = (int) ($bus->seat_count ?? 0);
        if ($totalSeats <= 0 || $totalSeats > 100) return [];

        $booked = $trip->booked_seats ?? [];

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
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
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
