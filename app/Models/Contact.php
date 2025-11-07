<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status', // 0: chưa xử lý, 1: đã phản hồi
    ];

    // -------------------------------
    // 🧩 ACCESSORS / HELPERS
    // -------------------------------

    // Hiển thị trạng thái dạng text
    public function getStatusLabelAttribute()
    {
        return $this->status == 1 ? 'Đã phản hồi' : 'Chưa xử lý';
    }

    // Hiển thị trạng thái với badge màu (cho admin)
    public function getStatusBadgeAttribute()
    {
        return $this->status == 1
            ? '<span class="badge bg-success">Đã phản hồi</span>'
            : '<span class="badge bg-warning text-dark">Chưa xử lý</span>';
    }
}
