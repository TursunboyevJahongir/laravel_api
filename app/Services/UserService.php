<?php


namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Services\CoreService;
use App\Events\UpdateImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UserService extends CoreService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function created(Model $model, array $data): void
    {
        (data_get($data, 'roles') && !in_array('superadmin', $data['roles'])) ?
            $this->repository->syncRoleToUser($model, $data['roles']) :
            $this->repository->syncRoleToUser($model, ['customer']);

        if (is_file($data['avatar'])) {
            UpdateImage::dispatch($data['avatar'], $model->avatar());
        }
    }

    public function updating(Model $model, array &$data): void
    {
        if (data_get($data, 'roles')) {
            $this->repository->syncRoleToUser($model, $data['roles']);
        }
    }

    public function updated(Model $model, array $data): void
    {
        if (is_file(data_get($data, 'avatar'))) {
            UpdateImage::dispatch($data['avatar'], $model->avatar());
        }
    }

    //public function deleting(Model $model)//you can use Observer or this
    //{
    //    DestroyFiles::dispatch($model->avatar->id);
    //}
}
