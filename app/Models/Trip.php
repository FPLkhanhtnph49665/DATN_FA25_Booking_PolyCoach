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
        'departure_date',
        'departure_time',
        'arrival_date',
        'arrival_time',
        'ticket_price',
        'status',
        'trip_code',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date'   => 'date',
        'departure_time' => 'datetime:H:i',
        'arrival_time'   => 'datetime:H:i',
    ];

    // Relations
    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    public function passengers()
    {
        return $this->hasMany(Booking::class, 'trip_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'trip_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Helper: count available seats
    public function availableSeats(): int
    {
        if (!$this->bus) return 0;

        $bookedSeats = $this->tickets->flatMap(fn($t) => $t->passengers ?? collect())
                                     ->pluck('seat_code')
                                     ->filter()
                                     ->map(fn($s) => strtoupper(trim($s)))
                                     ->all();

        $allSeats = range(1, $this->bus->seat_count); // assume seats are 1..N
        $available = array_diff($allSeats, $bookedSeats);

        return count($available);
    }

    // Helper: available seats in rows
    public function availableSeatsInRows(array $rows): int
    {
        // Example mapping
        $seatRows = [
            'front'  => ['A01','A02','A03','A04','B01','B02','B03','B04'],
            'middle' => ['A05','A06','A07','A08','B05','B06','B07','B08'],
            'back'   => ['A09','A10','A11','A12','B09','B10','B11','B12'],
        ];

        $allowedSeats = [];
        foreach ($rows as $r) {
            $allowedSeats = array_merge($allowedSeats, $seatRows[$r] ?? []);
        }

        $bookedSeats = $this->tickets->flatMap(fn($t) => $t->passengers ?? collect())
                                     ->pluck('seat_code')
                                     ->filter()
                                     ->map(fn($s) => strtoupper(trim($s)))
                                     ->all();

        $availableInRows = array_diff($allowedSeats, $bookedSeats);
        return count($availableInRows);
    }

    // Helper: booked seats
    public function getBookedSeats(): array
    {
        return $this->tickets->flatMap(fn($t) => $t->passengers ?? collect())
                             ->pluck('seat_code')
                             ->filter()
                             ->map(fn($s) => strtoupper(trim($s)))
                             ->all();
    }

    // Accessor formatted times
    public function getDepartureTimeFormattedAttribute()
    {
        return $this->departure_time?->format('H:i');
    }

    public function getArrivalTimeFormattedAttribute()
    {
        return $this->arrival_time?->format('H:i');
    }
}
