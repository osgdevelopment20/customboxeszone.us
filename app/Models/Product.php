<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Scout\Searchable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\SchemaOrg\Schema;

class Product extends Model implements HasMedia
{
    use HasFactory,
        InteractsWithMedia,
        Sluggable;



    protected $fillable = ['title'];
    public $timestamps = FALSE;

    public function registerMediaConversions(Media $media = null): void
    {

        $this->addMediaConversion('s')
            ->fit(Manipulations::FIT_CONTAIN, 280, 280)
            ->nonQueued()
            ->format('webp')
            ->quality(100);


        $this->addMediaConversion('l')
            ->fit(Manipulations::FIT_CONTAIN, 590, 590)
            ->nonQueued()
            ->format('webp')
            ->quality(100);
    }

    public function getMainMedia($second = false, $collection = 'product_gallery')
    {
        $media = $this->getMedia('product_gallery');

        if ($second) {
            $secondMedia = $media->skip(1)->first();
            if ($secondMedia) {
                return (object) [
                    'webp' => $secondMedia->getUrl('l'),
                    'name' => $secondMedia->name
                ];
            }
        }


        $mainMedia = $media->first();
        if ($mainMedia) {
            return (object) [
                'webp' => $mainMedia->getUrl('l'),
                'name' => $mainMedia->name
            ];
        }


    }


    public function getSchemaOrgAttribute()
    {
        $image = asset('og_image.jpeg');

        foreach ($this->getMedia('product_front_image') as $media) {
            $image = $media->getUrl('l');
        }


        $schema = Schema::product()
            ->identifier(route('page', $this->slug))
            ->name($this->product_name)
            ->image($image)
            ->description($this->meta_description)
            ->brand(Schema::brand()->name(config('app.name')))
            ->sku($this->product_name . '_' . $this->id)
            ->mpn("mpn_{$this->id}");

        return $schema;
    }


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'product_name'
            ]
        ];
    }
    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }
    public function variations()
    {
        return $this->hasMany(ProductVariation::class)->orderBy('sort_order');
    }

       public function clientStoriesResolved(): array
    {
        $stories = json_decode($this->client_story, true) ?: [];
        $mediaById = $this->getMedia('client_stories')->keyBy('id');
     
        return array_map(function ($story) use ($mediaById) {
            $mediaId = $story['media_id'] ?? null;
            $media = $mediaId ? $mediaById->get($mediaId) : null;

 //dd($mediaId,$media,$story);
            // Fall back to legacy s3 path for stories not yet migrated into the media library.
            $image = $media
                ? $media->getUrl()
                : (!empty($story['image']) ? Storage::disk('public')->url($story['image']) : '');
                
            return [
                'description' => $story['description'] ?? '',
                'media_id' => $mediaId,
                'image' => $image,
            ];
        }, $stories);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }


    public function scopeActive($query)
    {
        $query->where('status', 1);
    }

    public function scopeDisplayHomepage($query)
    {
        $query->where('display_homepage', 1);
    }

    public function scopeByIndustry($query)
    {
        $query->where('by_industry', 1);
    }

    public function scopeByStyle($query)
    {
        $query->where('by_style', 1);
    }

    public function scopeByMaterial($query)
    {
        $query->where('by_material', 1);
    }




}
