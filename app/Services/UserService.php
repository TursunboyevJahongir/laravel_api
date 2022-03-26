<?php


namespace App\Services;

use App\Contracts\UserRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Core\Models\CoreModel;
use App\Core\Services\CoreService;
use App\Events\UpdateImage;
use App\Models\User;
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

    /**
     * @param FormRequest $request
     * @param mixed ...$appends
     *
     * @return Collection|LengthAwarePaginator
     */
    public function get(FormRequest $request, ...$appends): Collection|LengthAwarePaginator
    {
        $query = $this->repository->mainQuery($request->get('columns', ['*']),
            $request->get('relations', []),
            $request->get('status'),
            $request->get('start', 1),
            $request->get('search'),
            $request->get('filters'),
            $request->get('not_filters'),
            $request->get('filterBy', 'id'),
            $request->get('order', 'desc'));

        $query = $this->repository->selfExclude($query,
            $request->get('self_exclude', false));
        $query = $this->repository->filterByRole($query, $request->get('role'));

        return match ($request->get('list_type')) {
            'collection' => $this->repository->collection($query,
                $request->get('limit', config('app.page_size')),
                $request->get('appends', [])),
            default => $this->repository->pagination($query,
                $request->get('per_page', config('app.pagination_size')),
                $request->get('appends', [])),
        };
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
        $validated = $request->validated();
        DB::transaction(function () use ($request, $validated, $user) {
            if ($request->exists('roles')) {
                $this->repository->syncRoleToUser($user, $request['roles']);
            }

            return $this->repository->update($user, $validated);
        });

        if ($request->hasFile('avatar')) {
            UpdateImage::dispatch($request['avatar'], $user->avatar(), User::USER_AVATAR_RESOURCES, User::PATH);
        }

        return true;
    }
}
