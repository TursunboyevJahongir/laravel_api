<?php

namespace App\Services;

use App\Contracts\{ProductServiceContract, ProductRepositoryContract};
use App\Core\Services\CoreService;
use App\Events\{AttachImages, DestroyFiles, UpdateFile, UpdateImage};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class ProductService extends CoreService implements ProductServiceContract
{
    public function __construct(ProductRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function created(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('mainImage')) {
            UpdateImage::dispatch($request['mainImage'], $model->mainImage(), $model->getFilePath(), $model::MAIN_IMAGE);
        }
        if ($request->hasFile('video')) {
            UpdateFile::dispatch($request['video'], $model->video(), $model->getFilePath(), $model::VIDEO);
        }
        if ($request->hasFile('images')) {
            AttachImages::dispatch($request['images'], $model->images(), $model->getFilePath(), $model::IMAGES);
        }
    }

    public function updated(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('mainImage')) {
            UpdateImage::dispatch($request['mainImage'], $model->mainImage(), $model->getFilePath(), $model::MAIN_IMAGE);
        }
        if ($request->hasFile('video')) {
            UpdateFile::dispatch($request['video'], $model->video(), $model->getFilePath(), $model::VIDEO);
        }
        if ($request->hasFile('images')) {
            AttachImages::dispatch($request['images'], $model->images(), $model->getFilePath(), $model::IMAGES);
        }
    }

    public function deleting(Model $model)
    {
        DestroyFiles::dispatch($model->images->pluck('id')->toArray());
        DestroyFiles::dispatch($model->mainImage->id);
        DestroyFiles::dispatch($model->video->id);
    }
}
