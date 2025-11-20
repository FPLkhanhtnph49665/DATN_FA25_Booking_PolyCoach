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
}
