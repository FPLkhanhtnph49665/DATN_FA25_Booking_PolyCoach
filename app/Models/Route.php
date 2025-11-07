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
        'trang_thai',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // 🔗 Quan hệ
    // =====================
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    // =====================
    // 💡 Accessors
    // =====================
    public function getTenTuyenAttribute(): string
    {
        return "{$this->diem_di} → {$this->diem_den}";
    }

    public function getTrangThaiLabelAttribute(): string
    {
        return match ((int)$this->trang_thai) {
            1 => '<span class="badge bg-success">Đang hoạt động</span>',
            0 => '<span class="badge bg-secondary">Tạm ngưng</span>',
            default => '<span class="badge bg-dark">Không xác định</span>',
        };
    }

    public function getQuangDuongLabelAttribute(): string
    {
        return number_format($this->quang_duong, 0, ',', '.') . ' km';
    }

    public function getThoiGianDuKienLabelAttribute(): string
    {
        return "{$this->thoi_gian_du_kien} giờ";
    }

    // =====================
    // 🔍 Scopes
    // =====================
    public function scopeActive($query)
    {
        return $query->where('trang_thai', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('trang_thai', 0);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('diem_di', 'like', "%$keyword%")
              ->orWhere('diem_den', 'like', "%$keyword%");
        });
    }
}
