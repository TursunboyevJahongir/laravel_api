<?php


namespace App\Services;

use App\Repositories\UserRepository;
use App\Core\Services\CoreService;
use App\Events\UpdateImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UserService extends CoreService
{
    public function __construct(UserRepository $repository)
    {
        parent::__construct($repository);
    }

    public function created(Model $model, FormRequest $request): void
    {
        ($request->except('roles') && !in_array('superadmin', $request['roles'])) ?
            $this->repository->syncRoleToUser($model, $request['roles']) :
            $this->repository->syncRoleToUser($model, ['customer']);

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $model->avatar());
        }
    }

    public function updating(Model $model, FormRequest &$request): void
    {
        if ($request->exists('roles')) {
            $this->repository->syncRoleToUser($model, $request['roles']);
        }
    }

    public function updated(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $model->avatar());
        }
    }

    //public function deleting(Model $model)//you can use Observer or this
    //{
    //    DestroyFiles::dispatch($model->avatar->id);
    //}
}
