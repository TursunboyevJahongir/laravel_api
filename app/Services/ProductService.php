<?php

namespace App\Services;

use App\Repositories\{ProductRepository};
use App\Core\Services\CoreService;
use App\Events\{AttachImages, DestroyFiles, UpdateFile, UpdateImage};
use Illuminate\Database\Eloquent\Model;

class ProductService extends CoreService
{
    public function __construct(ProductRepository $repository)
    {
        parent::__construct($repository);
    }

    private function checkFile(Model $model, array $data)
    {
        if (is_file(data_get($data, 'mainImage'))) {
            UpdateImage::dispatch($data['mainImage'], $model->mainImage(), $model->getFilePath(), $model::MAIN_IMAGE);
        }
        if (is_file(data_get($data, 'video'))) {
            UpdateFile::dispatch($data['video'], $model->video(), $model->getFilePath(), $model::VIDEO);
        }
        if (data_get($data, 'images')) {
            AttachImages::dispatch($data['images'], $model->images(), $model->getFilePath(), $model::IMAGES);
        }
    }

    public function created(Model $model, array $data): void
    {
        $this->checkFile($model, $data);
    }

    public function updated(Model $model, array $data): void
    {
        $this->checkFile($model, $data);
    }

    public function deleting(Model $model)
    {
        DestroyFiles::dispatch($model->images?->pluck('id')->toArray());
        DestroyFiles::dispatch($model->mainImage?->id);
        DestroyFiles::dispatch($model->video?->id);
    }
}
