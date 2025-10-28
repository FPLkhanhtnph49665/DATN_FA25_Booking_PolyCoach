<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bien_so',
        'so_ghe',
        'loai_xe',
        'trang_thai'
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
