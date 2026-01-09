<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'title',
    'slug',
    'thumbnail',
    'excerpt',
    'content',
    'category',
    'is_featured',
    'status',
    'published_at',
];



    protected $casts = [
        'published_at' => 'datetime',
    ];
}
