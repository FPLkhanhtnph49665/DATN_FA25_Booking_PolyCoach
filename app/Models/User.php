<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Các cột cho phép mass assignment
     */
    protected $fillable = [
        'user_code',
        'first_name',
        'last_name',
        'full_name',
        'image',
        'email',
        'phone',
        'password',
        'role',
        'status',
    ];

    /**
     * Ẩn khi trả JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast kiểu dữ liệu
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Mask phone để hiển thị
     */
    public function getMaskedPhoneAttribute()
    {
        if (!$this->phone) return '-';

        $length = strlen($this->phone);
        return substr($this->phone, 0, 3)
            . str_repeat('*', max($length - 6, 0))
            . substr($this->phone, -3);
    }

    /**
     * BOOTED – sinh user_code & full_name
     */
    protected static function booted()
    {
        /**
         * Khi tạo mới user
         */
        static::creating(function ($user) {

            // ====== SINH USER CODE (KHÔNG BAO GIỜ TRÙNG) ======
            if (empty($user->user_code)) {

                // LẤY USER_CODE LỚN NHẤT TỪNG TỒN TẠI (KỂ CẢ ĐÃ SOFT DELETE)
                $lastUser = static::withTrashed()
                    ->where('user_code', 'like', 'DATN-FA25-%')
                    ->orderBy('user_code', 'desc')
                    ->first();

                $nextNumber = $lastUser
                    ? (int) substr($lastUser->user_code, -4) + 1
                    : 1;

                $user->user_code = 'DATN-FA25-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }

            // ====== FULL NAME ======
            if (empty($user->full_name)) {
                $user->full_name = trim(
                    ($user->first_name ?? '') . ' ' . ($user->last_name ?? '')
                );
            }
        });

        /**
         * Khi cập nhật user
         */
        static::updating(function ($user) {
            if ($user->isDirty(['first_name', 'last_name'])) {
                $user->full_name = trim(
                    ($user->first_name ?? '') . ' ' . ($user->last_name ?? '')
                );
            }
        });
    }

    /**
     * Helpers
     */
    public function isRoleAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relationships
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
