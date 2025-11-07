<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'so_tien',
        'phuong_thuc',
        'trang_thai',
        'ma_giao_dich'
    ];

    protected $casts = [
        'so_tien' => 'float',
    ];

    // Mối quan hệ
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: lọc theo trạng thái
    public function scopePaid($query)
    {
        return $query->where('trang_thai', 'paid');
    }

    // Accessor: định dạng số tiền
    public function getSoTienFormattedAttribute()
    {
        return number_format($this->so_tien, 0, ',', '.') . ' VNĐ';
    }
}
