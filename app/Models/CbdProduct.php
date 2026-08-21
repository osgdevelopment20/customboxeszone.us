<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbdProduct extends Model
{
    use HasFactory,
        Sluggable;


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'product_title'
            ]
        ];
    }

    public function cbdcategory()
    {
        return $this->belongsTo(CbdCategory::class,'category_id');
    }
    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
    public function scopeDisplayHomepage($query)
    {
        $query->where('display_homepage', 1);
    }
}
