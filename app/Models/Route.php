<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'diem_di',
        'diem_den',
        'quang_duong',
        'thoi_gian_du_kien',
        'trang_thai'
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
