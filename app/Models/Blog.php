<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Traits\RestrictsMediaToImages;

class Blog extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, RestrictsMediaToImages, Sluggable;


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->imageCollection('blog_image')->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('resized')
            ->fit(Manipulations::FIT_CONTAIN, 800, 400)
            ->nonQueued();
    }

    public function getBlogImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('blog_image');

        if ($media) {
            return $media->hasGeneratedConversion('resized')
                ? $media->getUrl('resized')
                : $media->getUrl();
        }

        // Fallback for legacy images not yet migrated into the media library.
        if (!empty($this->blog_image)) {
            return Storage::disk('s3')->url('blogs/' . $this->blog_image);
        }

        return null;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        $query->where('status', 1);
    }
}
