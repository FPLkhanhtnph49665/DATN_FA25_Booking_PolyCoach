<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'user_code',
        'first_name',
        'last_name',
        'full_name',
        'image',
        'email',
        'phone',
        'password',
        'role',    // 'admin' | 'customer'
        'status',  // 1: active, 0: blocked
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function getMaskedPhoneAttribute()
    {
        if (!$this->phone) return '-';
        $length = strlen($this->phone);
        return substr($this->phone, 0, 3) . str_repeat('*', max($length - 6, 0)) . substr($this->phone, -3);
    }
    protected static function booted()
    {
        static::creating(function ($user) {
            $lastUser = User::latest('id')->first();
            $number = $lastUser ? $lastUser->id + 1 : 1;
            $user->user_code = 'DATN_FA25_PoLyCoach_' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }



    public function isRoleAdmin(): bool
    {
        return $this->role === 'admin'; // hoặc 'is_admin' nếu bạn dùng cột này
    }


    // Relationship
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
