<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passenger extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'name',
        'phone',
        'age',
        'seat_number'
    ];

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    // Mỗi hành khách thuộc 1 vé
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // Hành khách -> User (thông qua vé)
    public function user()
    {
        return $this->hasOneThrough(
            User::class,
            Ticket::class,
            'id',        // Ticket PK
            'id',        // User PK
            'ticket_id', // Passenger FK
            'user_id'    // Ticket FK
        );
    }

    // Hành khách -> Trip (thông qua vé)
    public function trip()
    {
        return $this->hasOneThrough(
            Trip::class,
            Ticket::class,
            'id',        // Ticket PK
            'id',        // Trip PK
            'ticket_id', // Passenger FK
            'trip_id'    // Ticket FK
        );
    }

    // -------------------------------
    // 🔍 SCOPES
    // -------------------------------

    // Lọc hành khách có vé đã thanh toán
    public function scopePaid($query)
    {
        return $query->whereHas('ticket', function ($q) {
            $q->where('trang_thai', 'paid');
        });
    }

    // -------------------------------
    // 🧩 ACCESSORS
    // -------------------------------

    public function getSeatLabelAttribute()
    {
        return strtoupper($this->seat_number);
    }

    public function getDisplayNameAttribute()
    {
        return $this->name . ($this->age ? " ({$this->age} tuổi)" : '');
    }
}
