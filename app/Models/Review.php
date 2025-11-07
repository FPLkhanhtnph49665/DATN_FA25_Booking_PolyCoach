<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'trip_id',
        'rating',       // điểm đánh giá (1-5)
        'noi_dung',     // nội dung đánh giá
        'status',       // pending | approved | rejected
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($review) {
            // Trạng thái mặc định khi tạo mới
            $review->status = $review->status ?? 'pending';
        });
    }

    /* ---------------------------
       QUAN HỆ
    ----------------------------*/

    // Người viết đánh giá
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Chuyến xe được đánh giá
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /* ---------------------------
       SCOPES
    ----------------------------*/

    // Chỉ lấy đánh giá đã duyệt
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Lọc theo chuyến
    public function scopeForTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    // Lọc theo số sao
    public function scopeRating($query, $stars)
    {
        return $query->where('rating', $stars);
    }

    /* ---------------------------
       ACCESSORS
    ----------------------------*/

    // Trả về chuỗi sao (⭐️⭐️⭐️)
    public function getStarsAttribute()
    {
        return str_repeat('⭐', (int) round($this->rating));
    }

    // Thời gian hiển thị dễ đọc hơn
    public function getFormattedDateAttribute()
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '';
    }

    /* ---------------------------
       THỐNG KÊ / TÍNH TRUNG BÌNH
    ----------------------------*/

    // Tính trung bình điểm rating của 1 chuyến
    public static function averageRatingByTrip($tripId)
    {
        return static::where('trip_id', $tripId)
            ->where('status', 'approved')
            ->avg('rating') ?? 0;
    }

    // Đếm số lượt đánh giá theo chuyến
    public static function countByTrip($tripId)
    {
        return static::where('trip_id', $tripId)
            ->where('status', 'approved')
            ->count();
    }
}
