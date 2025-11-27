<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DropoffPoint extends Model
{
    protected $fillable = [
        'route_id',
        'ten_diem_tra',
        'dia_chi',
        'order',
    ];

    // Quan hệ: Một điểm trả thuộc về một Route
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
