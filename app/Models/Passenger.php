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

    // Quan hệ với vé
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
    public function user()
{
    return $this->ticket?->user;
}


    // Quan hệ với chuyến qua vé
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

    // Scope hành khách theo vé đã thanh toán
    public function scopePaid($query)
    {
        return $query->whereHas('ticket', function($q){
            $q->where('trang_thai', 'paid');
        });
    }

    // Accessor ghế
    public function getSeatLabelAttribute()
    {
        return $this->seat_number;
    }
}
