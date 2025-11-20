<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i',
        'updated_at' => 'datetime:d/m/Y H:i',
    ];

    // =====================
    // 🔗 Quan hệ
    // =====================

    public function routesFrom()
    {
        return $this->hasMany(Route::class, 'from_city_id');
    }

    public function routesTo()
    {
        return $this->hasMany(Route::class, 'to_city_id');
    }

    // =====================
    // 💡 Accessors
    // =====================
    public function getStatusLabelAttribute(): string
    {
        return $this->status ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>';
    }

    // =====================
    // 🔍 Scopes
    // =====================
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where('name', 'like', "%$keyword%");
    }
}
