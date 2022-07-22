<?php


namespace App\Services;

use App\Contracts\CategoryServiceContract;
use App\Contracts\CategoryRepositoryContract;
use App\Core\Services\CoreService;
use App\Events\DestroyImages;
use App\Events\UpdateImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class CategoryService extends CoreService implements CategoryServiceContract
{
    public function __construct(CategoryRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function created(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('ico')) {
            UpdateImage::dispatch($request['ico'], $model->ico());
        }
    }

    public function updated(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('ico')) {
            UpdateImage::dispatch($request['ico'], $model->ico());
        }
    }

    public function deleting(Model $model)//you can use Observer or this
    {
        DestroyImages::dispatch($model->ico->id);
    }
}
