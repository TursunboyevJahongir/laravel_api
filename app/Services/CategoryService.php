<?php


namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Core\Services\CoreService;
use App\Events\DestroyFiles;
use App\Events\UpdateImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class CategoryService extends CoreService
{
    public function __construct(CategoryRepository $repository)
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
        DestroyFiles::dispatch($model->ico->id);
    }
}
