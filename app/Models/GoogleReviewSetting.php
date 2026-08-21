<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoogleReviewSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'place_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public static function getSettings()
    {
        return self::first() ?? new self();
    }
}
