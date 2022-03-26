<?php


namespace App\Services;

use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Models\CoreModel;
use App\Core\Services\CoreService;
use App\Events\UpdateImage;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class UserService extends CoreService implements UserServiceContract
{
    public function __construct(UserRepositoryContract $repository)
    {
        parent::__construct($repository);
    }


    public function create(FormRequest $request): mixed
    {
        return DB::transaction(function () use ($request) {
            $user = $this->repository->create($request->validated());
            ($request->except('roles') && !in_array('superadmin', $request['roles'])) ?
                $this->repository->syncRoleToUser($user, $request['roles']) :
                $this->repository->syncRoleToUser($user, ['customer']);
            return $user;
        });
    }

    public function update(CoreModel $user, FormRequest $request): bool
    {
        $validated = $request->validated();
        DB::transaction(function () use ($request, $validated, $user) {
            if ($request->exists('roles')) {
                $this->repository->syncRoleToUser($user, $validated['roles']);
            }

            return $this->repository->update($user, $validated);
        });

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $user->avatar(), User::USER_AVATAR_RESOURCES, User::PATH);
        }

        return true;
    }
}
