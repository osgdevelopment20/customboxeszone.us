<?php

namespace App\Traits;

use Spatie\MediaLibrary\MediaCollections\File;
use Spatie\MediaLibrary\MediaCollections\MediaCollection;

/**
 * Media library keeps the uploaded file's original name and extension, so a
 * collection must check both the detected content type and the extension.
 * Checking only one lets an image/script polyglot through.
 */
trait RestrictsMediaToImages
{
    protected function imageCollection(string $name): MediaCollection
    {
        return $this->addMediaCollection($name)->acceptsFile(function (File $file) {
            $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'];
            $mimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];

            return in_array(strtolower(pathinfo($file->name, PATHINFO_EXTENSION)), $extensions, true)
                && in_array($file->mimeType, $mimeTypes, true);
        });
    }
}
