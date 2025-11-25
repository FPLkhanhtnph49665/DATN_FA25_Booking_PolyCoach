<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusSeat extends Model
{
    protected $fillable = [
        'bus_id',
        'code',
        'floor',
        'row',
        'col',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status ? 'Sử dụng' : 'Khóa / Không bán';
    }
}
