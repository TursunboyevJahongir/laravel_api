<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use App\Contracts\ResourceRepositoryContract;
use App\Contracts\ResourceServiceContract;

class ResourceService implements ResourceServiceContract
{
    /**
     * @param ResourceRepositoryContract $repository
     */
    public function __construct(protected ResourceRepositoryContract $repository)
    {
    }

    /**
     * @param array $images
     * @param MorphOne|MorphMany|MorphToMany $relation
     * @param string $path
     * @param string|null $identifier
     */
    public function attachImages(
        array $images,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    ) {
        foreach ($images as $image) {
            $this->saveImage($image, $relation, $path, $identifier);
        }
    }

    /**
     * @param UploadedFile $file
     * @param MorphOne|MorphMany|MorphToMany $relation
     * @param string $path
     * @param string|null $identifier
     */
    public function saveImage(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    ) {
        $type     = $file->getClientOriginalExtension();
        $fileName = md5(time() . $file->getFilename()) . '.' . $type;
        $file->storeAs("$path/original", $fileName);
        $this->imageCrop($file, $fileName, "$path/1024/", 1024, 1024);
        $this->imageCrop($file, $fileName, "$path/512/", 512, 512);
        $this->repository->create($relation, $type, $identifier, "uploads/$path/original/$fileName", "uploads/$path/1024/$fileName", "uploads/$path/512/$fileName");
    }

    /**
     * @param UploadedFile $file
     * @param MorphOne|MorphMany|MorphToMany $relation
     * @param string $path
     * @param string|null $identifier
     */
    public function updateImage(
        UploadedFile $file,
        MorphOne|MorphMany|MorphToMany $relation,
        string $path = 'files',
        string $identifier = null
    ) {
        $this->deleteImage($relation);
        $this->saveImage($file, $relation, $path, $identifier);
    }

    /**
     * @param $images
     */
    public function destroyImages($images)
    {
        $this->repository->destroy(Arr::wrap($images));
    }

    /**
     * @param $relation
     */
    public function deleteImage($relation)
    {
        if ($relation->exists()) {
            $this->repository->removeFile($relation->first());
            $relation->delete();
        }
    }

    public function imageCrop(UploadedFile $file, $fileName, $path, int $width, int $height)
    {
        $image  = Image::make($file);
        $width  = $image->width() > $width ? $width : $image->width();
        $height = $image->height() > $height ? $height : $image->height();
        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        Storage::disk('public')->put("uploads/$path" . $fileName, (string)$image->encode());
    }
}
