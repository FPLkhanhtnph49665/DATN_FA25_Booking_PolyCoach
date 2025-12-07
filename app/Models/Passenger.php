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
        'seat_number',
    ];

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    // Mỗi hành khách thuộc 1 vé
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // ❗ Không dùng hasOneThrough ở đây vì hướng FK không đúng
    // Thay vào đó: dùng accessor cho tiện:

    // $passenger->user
    public function getUserAttribute()
    {
        return $this->ticket?->user;
    }

    // $passenger->trip
    public function getTripAttribute()
    {
        return $this->ticket?->trip;
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
}
