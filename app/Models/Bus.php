<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'plate_number', // license plate
        'seat_count',   // total seats
        'type',         // seat | sleeper | limousine
        'status',       // 1: active, 0: inactive
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    public function seats(){
        return $this->hasMany(BusSeat::class);
    }
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // -------------------------------
    // 🧮 ACCESSORS / HELPERS
    // -------------------------------

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'sleeper' => 'Sleeper',
            'seat'    => 'Seat',
            'limousine' => 'Limousine',
            default   => ucfirst(str_replace('_', ' ', $this->type ?? 'Unknown')),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status
            ? 'Active'
            : 'Inactive / Maintenance';
    }

    public function getTotalTripsAttribute(): int
    {
        return $this->trips()->count();
    }

    // -------------------------------
    // 🔍 SCOPES
    // -------------------------------
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }
    // Bus.php

public function generateDefaultSeats(): void
    {
        // Nếu xe đã có ghế rồi thì không sinh nữa (tránh trùng)
        if ($this->seats()->exists()) {
            return;
        }

        // Xác định số tầng
        $floors = ($this->type === 'sleeper') ? 2 : 1;

        // Prefix cho từng tầng (tầng 1 -> A, tầng 2 -> B, …)
        $prefixes = ['A', 'B', 'C', 'D'];

        $totalSeats = (int) $this->seat_count;
        if ($totalSeats <= 0) {
            return;
        }

        // Số ghế mỗi tầng (làm tròn lên nếu không chia hết)
        $seatsPerFloor = (int) ceil($totalSeats / $floors);

        // Chia layout 4 ghế / cột cho dễ nhìn (giống UI bạn đang dùng)
        $cols = 4;
        $rows = (int) ceil($seatsPerFloor / $cols);

        $created = 0;

        for ($floor = 0; $floor < $floors; $floor++) {
            $prefix = $prefixes[$floor] ?? $prefixes[0];

            for ($i = 0; $i < $seatsPerFloor; $i++) {
                if ($created >= $totalSeats) {
                    break 2; // đủ số ghế thì dừng luôn 2 vòng
                }

                $seatNumber = $i + 1; // 1..N trên từng tầng
                $code       = $prefix . str_pad($seatNumber, 2, '0', STR_PAD_LEFT);

                $row = (int) floor($i / $cols) + 1;
                $col = ($i % $cols) + 1;

                $this->seats()->create([
                    'code'   => $code,
                    'floor'  => $floor + 1, // 1-based
                    'row'    => $row,
                    'col'    => $col,
                    'status' => 1,
                ]);

                $created++;
            }
        }
    }

}
