<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbdCategory extends Model
{
    use HasFactory, Sluggable;



    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
    public function cbdproduct()
    {
        return $this->hasMany(CbdProduct::class,'category_id');
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
