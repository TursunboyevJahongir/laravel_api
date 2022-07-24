<?php

namespace App\Contracts;


use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;

interface ResourceServiceContract
{
    public function attachImages(
        array $images,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    );

    public function saveImage(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    );

    public function saveFile(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    );

    public function updateImage(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    );

    public function updateFile(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    );

    public function destroyImages(array $images);

    public function deleteFile($relation);

    public function imageCrop(UploadedFile $file, $fileName, $path, int $width, int $height);
}
