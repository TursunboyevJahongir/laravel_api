<?php


namespace App\Services;

use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Models\CoreModel;
use App\Core\Services\CoreService;
use App\Events\DestroyImages;
use App\Events\UpdateImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class UserService extends CoreService implements UserServiceContract
{
    public function __construct(UserRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function appends(Builder $query, ...$appends)
    {
        $this->repository->selfExclude($query, request()->get('self_exclude', false));

        $this->repository->filterByRole($query, request()->get('role'));
    }

    public function created(Model|CoreModel $model, FormRequest $request): void
    {
        ($request->except('roles') && !in_array('superadmin', $request['roles'])) ?
            $this->repository->syncRoleToUser($model, $request['roles']) :
            $this->repository->syncRoleToUser($model, ['customer']);

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $model->avatar());
        }
    }

    public function updating(Model|CoreModel $model, FormRequest $request): FormRequest
    {
        if ($request->exists('roles')) {
            $this->repository->syncRoleToUser($model, $request['roles']);
        }

        return $request;
    }

    public function updated(Model|CoreModel $model, FormRequest $request): void
    {
        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $model->avatar());
        }
    }

    //public function deleting(Model|CoreModel $model)//you can use Observer or this
    //{
    //    DestroyImages::dispatch($model->avatar->id);
    //}
}
