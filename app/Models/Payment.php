<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',       // FK to tickets
        'user_id',         // FK to users
        'amount',          // payment amount
        'payment_method',          // payment method: cash / momo / bank / etc.
        'status',          // paid / pending / failed
        'transaction_code' // unique transaction identifier
    ];

    protected $casts = [
        'amount' => 'float',
    ];

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    // -------------------------------
    // 🔍 SCOPES
    // -------------------------------

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // -------------------------------
    // 🧮 ACCESSORS / HELPERS
    // -------------------------------

    public function getAmountFormattedAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.') . ' VND';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}
