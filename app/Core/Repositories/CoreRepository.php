<?php

namespace App\Core\Repositories;

use App\Core\Contracts\CoreRepositoryContract;
use Illuminate\Database\Eloquent\{Builder, Model, Relations\Pivot};

abstract class CoreRepository implements CoreRepositoryContract
{
    public Model|Pivot $model;

    /**
     * @param Model|Pivot $model
     */
    public function __construct(Model|Pivot $model)
    {
        $this->model = $model;
    }

    /**
     * for any checks
     *
     * @param Builder|Model $query
     *
     * @return void
     */
    public function availability(
        Builder|Model $query
    ): void {
    }

    public function query(
        array $columns = ['*'],
        array $relations = [],
        bool $trashed = false,
        Builder|null $query = null
    ): Builder {
        $columns   = request()->get('columns', ['*']);
        $relations = request()->get('relations', []);
        $trashed   = request()->get('only_deleted', false);

        return $this->mainQuery($columns, $relations, $trashed, $query);
    }

    public function mainQuery(
        array $columns = [' * '],
        array $relations = [],
        bool $trashed = false,
        Builder|null $query = null
    ): Builder {
        return $this->model
            ->select($columns)
            ->closure($this, 'availability')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->with($relations);
    }

    /**
     * Show entity
     *
     * @param mixed $value
     * @param string $column
     *
     * @return Model|null
     */
    public function show(
        mixed $value,
        string $column = 'id'
    ): ?Model {
        $value = ($value instanceof Model) ? $value->id : $value;

        return $this->firstBy($value, $column);
    }

    /**
     * Create element
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update element
     *
     * @param Model|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(Model|int $model, array $payload): bool
    {
        return $this->show($model)->update($payload);
    }

    /**
     * Delete element
     *
     * @param Model|int $model
     *
     * @return bool
     */
    public function delete(Model|int $model): bool
    {
        return $this->show($model)->delete();
    }

    /**
     * Find element by id
     *
     * @param mixed $value
     * @param string $column
     *
     * @return Model|null
     */
    public function firstBy(
        mixed $value,
        string $column = 'id'
    ): ?Model {
        return $this->query()
            ->where($column, $value)
            ->firstOrFail()
            ->append(request()->get('appends', []));
    }
}
