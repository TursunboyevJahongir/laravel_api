<?php

namespace App\Contracts;


use Illuminate\Http\UploadedFile;

interface ResourceServiceContract
{
    public function attachImages(array $images, $relation, string $identifier, string $path);

    public function saveImage(UploadedFile $file, $relation, string $identifier, string $path);

    public function updateImage(UploadedFile $file, $relation, string $identifier, string $path);

    public function destroyImages(array $images);

    public function deleteImage($relation);

    public function imageCrop(UploadedFile $file, $fileName, $path, int $width, int $height);
}
