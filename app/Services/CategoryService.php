<?php


namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Core\Services\CoreService;
use App\Events\{DestroyFiles, UpdateImage};
use Illuminate\Database\Eloquent\Model;

class CategoryService extends CoreService
{
    public function __construct(CategoryRepository $repository)
    {
        parent::__construct($repository);
    }

    private function checkFile(Model $model, array $data)
    {
        if (is_file(data_get($data, 'ico'))) {
            UpdateImage::dispatch($data['ico'], $model->ico());
        }
    }

    public function created(Model $model, array $data): void
    {
        $this->checkFile($model, $data);
        $model->loadMissing('ico');
    }

    public function updated(Model $model, array $data): void
    {
        $this->checkFile($model, $data);
    }

    public function deleting(Model $model)//you can use Observer or this
    {
        DestroyFiles::dispatch($model->ico?->id);
    }
}
