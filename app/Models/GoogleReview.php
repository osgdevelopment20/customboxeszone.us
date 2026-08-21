<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'reviewer_name',
        'reviewer_photo',
        'review_text',
        'rating',
        'review_date',
        'google_review_id',
        'reviewer_reviews_count',
        'is_active'
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'is_active' => 'boolean',
        'rating' => 'integer',
        'reviewer_reviews_count' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('review_date', 'desc');
    }
}
