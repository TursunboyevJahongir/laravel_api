<?php

namespace App\Repositories;

use App\Core\Repositories\CoreRepository;
use App\Models\RefreshToken;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class UserRepository extends CoreRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function selfExclude(
        Builder $query,
        bool $selfExclude = false
    ) {
        return $query->when($selfExclude, fn($q) => $q->where('id', '!=', auth()->id()));
    }

    public function filterByRole(
        Builder $query,
        array|string $role = null,
    ) {
        return $query->when($role, function ($query) use ($role) {
            $query->role($role);
        });
    }

    public function findByPhone(
        int $phone,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?Model {
        return $this->model
            ->closure($this, 'availability')
            ->with($relations)
            ->wherePhone($phone)
            ->first($columns)
            ?->append($appends);
    }

    public function syncRoleToUser(
        Model|int $user,
        array|int|string $roles
    ) {
        $this->model = is_int($user) ? $this->findById($user) : $user;
        $this->model->syncRoles($roles);
    }

    public function generateRefreshToken(Model $user): RefreshToken
    {
        $token = $user->createToken('user_' . $user->phone)->plainTextToken;

        return $user->token()->create(['token' => $token])->load('user');
    }

    public function findByRefreshToken(Request $request): ?RefreshToken
    {
        return RefreshToken::firstWhere('refresh_token', decrypt($request->bearerToken()));
    }

    public function findByToken(Request $request): ?RefreshToken
    {
        return RefreshToken::firstWhere('token', $request->bearerToken());
    }
}
