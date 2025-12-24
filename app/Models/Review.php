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
        'route_id',
        'rating',    // rating score (1-5)
        'content',   // review content
        'status',    // pending | approved | rejected
    ];

    protected $casts = [
        'rating' => 'float',
    ];

    protected static function booted()
    {
        static::creating(function ($review) {
            // default status when creating a new review
            $review->status = $review->status ?? 'pending';
        });
    }

    // -------------------------------
    // 🔗 RELATIONSHIPS
    // -------------------------------

    // Author of the review
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Trip being reviewed
    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
    // Route being reviewed
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }

    // -------------------------------
    // 🔍 SCOPES
    // -------------------------------

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeForTrip($query, $tripId)
    {
        return $query->where('trip_id', $tripId);
    }

    public function scopeRating($query, $stars)
    {
        return $query->where('rating', $stars);
    }

    // -------------------------------
    // 🧮 ACCESSORS / HELPERS
    // -------------------------------

    // Returns star symbols: ⭐️⭐️⭐️
    public function getStarsAttribute(): string
    {
        return str_repeat('⭐', (int) round($this->rating));
    }

    // Formatted created date
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at ? $this->created_at->format('d/m/Y H:i') : '';
    }

    // -------------------------------
    // 📊 STATISTICS
    // -------------------------------

    // Average rating for a trip
    public static function averageRatingByTrip($tripId): float
    {
        return static::where('trip_id', $tripId)
            ->where('status', 'approved')
            ->avg('rating') ?? 0;
    }

    // Count of approved reviews for a trip
    public static function countByTrip($tripId): int
    {
        return static::where('trip_id', $tripId)
            ->where('status', 'approved')
            ->count();
    }
}
