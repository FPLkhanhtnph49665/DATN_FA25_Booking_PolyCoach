<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_city_id',
        'to_city_id',
        'distance',
        'estimated_time',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // 🔗 Relationships
    // =====================
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
    // Một Route có nhiều điểm đón
    public function pickupPoints()
    {
        return $this->hasMany(PickupPoint::class)->orderBy('order');
    }

    // Một Route có nhiều điểm trả
    public function dropoffPoints()
    {
        return $this->hasMany(DropoffPoint::class)->orderBy('order');
    }
    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }
    public function toCity()
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }
    
    
    // =====================
    // 💡 Accessors
    // =====================

    public function getTenTuyenAttribute(): string
    {
        return "{$this->fromCity?->name} → {$this->toCity?->name}";
    }

    public function getTrangThaiLabelAttribute(): string
    {
        return match ((int) $this->status) {
            1 => '<span class="badge bg-success">Active</span>',
            0 => '<span class="badge bg-secondary">Inactive</span>',
            default => '<span class="badge bg-dark">Unknown</span>',
        };
    }
}
