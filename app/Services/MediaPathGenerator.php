<?php

namespace App\Services;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . '/responsive/';
    }

    protected function getBasePath(Media $media): string
    {
        $modelType = class_basename($media->model_type);
        $collectionName = $media->collection_name;

        // Organize by model type and collection
        switch ($modelType) {
            case 'Product':
                // Products: products/product_front_image/, products/product_back_image/, products/product_gallery/
                return "products/{$collectionName}/{$media->id}";

            case 'Category':
                // Categories: categories/{id}/
                return "categories/{$media->id}";

            case 'Blog':
                // Blogs: blogs/{id}/
                return "blogs/{$media->id}";

            default:
                // Fallback for other models
                return strtolower($modelType) . "s/{$media->id}";
        }
    }
}
