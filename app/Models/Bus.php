<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'bien_so',     // Biển số xe
        'so_ghe',      // Tổng số ghế
        'loai_xe',     // Ghế ngồi / Giường nằm
        'trang_thai',  // 1: hoạt động, 0: bảo trì
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

    // Hiển thị loại xe rõ ràng hơn
    public function getLoaiXeLabelAttribute()
    {
        return $this->loai_xe === 'giuong_nam' ? 'Giường nằm' : 'Ghế ngồi';
    }

    // Hiển thị trạng thái dạng text
    public function getTrangThaiLabelAttribute()
    {
        return $this->trang_thai ? 'Đang hoạt động' : 'Bảo trì';
    }

    // Ghế còn trống (tính toán dựa trên các chuyến đang mở)
    public function getTongChuyenAttribute()
    {
        return $this->trips()->count();
    }
}
