<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Category extends Model implements HasMedia
{
    use HasFactory, Sluggable, InteractsWithMedia;

    protected $fillable = ['title'];


    public function registerMediaConversions(Media $media = null): void
    {

        $this->addMediaConversion('dropdown-icon')
            ->fit(Manipulations::FIT_CONTAIN, 50, 50)
            ->nonQueued()
            ->format('webp')
            ->quality(100);

        $this->addMediaConversion('homepage')
            ->fit(Manipulations::FIT_CONTAIN, 600, 250)
            ->nonQueued()
            ->format('webp')
            ->quality(100);


        $this->addMediaConversion('l')
            ->fit(Manipulations::FIT_CONTAIN, 440, 440)
            ->nonQueued()
            ->format('webp')
            ->quality(100);

    }
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }



    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function procategories()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // query Scope

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeDisplayHomepage($query)
    {
        $query->where('display_homepage', 1);
    }

    public function scopeByStyle($query)
    {
        $query->where('by_style', 1);
    }

    public function scopeByMaterial($query)
    {
        $query->where('by_material', 1);
    }

    public function scopeByIndustry($query)
    {
        $query->where('by_industry', 1);
    }
    public function scopeIsParent($query, $slug)
    {
        $query->where('parent_id', '=', '0')->where('slug', $slug);
    }
    public function scopeNotParent($query, $slug)
    {
        return $query->where('parent_id', '!=', '0')->where('slug', $slug);
    }






}
