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
        'so_ghe',      // Tổng số ghế trên xe
        'loai_xe',     // ghe_ngoi | giuong_nam (hoặc giá trị bạn quy ước)
        'trang_thai',  // 1: hoạt động, 0: bảo trì/ngưng
    ];

    protected $casts = [
        'trang_thai' => 'boolean',
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
    public function getLoaiXeLabelAttribute(): string
    {
        return match ($this->loai_xe) {
            'giuong_nam' => 'Giường nằm',
            'ghe_ngoi'   => 'Ghế ngồi',
            default      => ucfirst(str_replace('_', ' ', $this->loai_xe ?? 'Không rõ')),
        };
    }

    // Hiển thị trạng thái dạng text
    public function getTrangThaiLabelAttribute(): string
    {
        return $this->trang_thai
            ? 'Đang hoạt động'
            : 'Bảo trì / Ngưng hoạt động';
    }

    // Tổng số chuyến mà xe này đã/đang được gán
    public function getTongChuyenAttribute(): int
    {
        return $this->trips()->count();
    }
}
