<?php


namespace App\Services;

use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Models\CoreModel;
use App\Core\Services\CoreService;
use App\Events\UpdateImage;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserService extends CoreService implements UserServiceContract
{
    public function __construct(UserRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function appends(Builder $query, ...$appends)
    {
        $this->repository->selfExclude($query, request()->get('role'));

        $this->repository->filterByRole($query, request()->get('role'));
    }


    public function create(FormRequest $request): mixed
    {
        $user = DB::transaction(function () use ($request) {
            $user = $this->repository->create($request->validated());
            ($request->except('roles') && !in_array('superadmin', $request['roles'])) ?
                $this->repository->syncRoleToUser($user, $request['roles']) :
                $this->repository->syncRoleToUser($user, ['customer']);

            return $user;
        });

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $user->avatar(), User::USER_AVATAR_RESOURCES, User::PATH);
        }

        return $user->load('roles', 'avatar');
    }

    public function update(CoreModel $user, FormRequest $request): bool
    {
        DB::transaction(function () use ($request, $user) {
            if ($request->exists('roles')) {
                $this->repository->syncRoleToUser($user, $request['roles']);
            }

            return $this->repository->update($user, $request->validated());
        });

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $user->avatar(), User::USER_AVATAR_RESOURCES, User::PATH);
        }

        return true;
    }
}
