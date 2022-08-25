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
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder {
        $columns   = request()->get('columns', ['*']);
        $relations = request()->get('relations', []);
        $trashed   = request()->get('only_deleted', false);
        $orderBy   = request()->get('order', 'id');
        $sort      = request()->get('sort', 'DESC');

        return $this->mainQuery($columns, $relations, $trashed, $orderBy, $sort, $query);
    }

    public function mainQuery(
        array $columns = [' * '],
        array $relations = [],
        bool $trashed = false,
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder {
        return $this->model
            ->select($columns)
            ->closure($this, 'availability')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->with($relations);
    }

    public function orderBy(
        Builder $query,
        string $orderBy = "id",
        string $sort = 'DESC'
    ) {
        if (str_contains($orderBy, ',')) {
            $fields = explode(',', $orderBy);
            foreach ($fields as $field) {
                $field = $this->model->isJson($field) ?
                    $field . "->" . app()->getLocale() : $field;
                $query->orderBy($field, $sort);
            }
        } else {
            $orderBy = $this->model->isJson($orderBy) ?
                $orderBy . "->" . app()->getLocale() : $orderBy;
            $query->orderBy($orderBy, $sort);
        }
    }

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return Model|null
     */
    public function show(
        Model|int $model,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        $id = ($model instanceof Model) ? $model->id : $model;

        return $this->findById($id, $columns, $relations, $appends);
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
        return ($model instanceof Model)
            ? $model->update($payload)
            : $this->findById($model)->update($payload);
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
        return ($model instanceof Model)
            ? $model->delete()
            : $this->findById($model)->delete();
    }

    /**
     * Find element by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return Model|null
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?Model {
        return $this->model
            ->closure($this, 'availability')
            ->select($columns)
            ->with($relations)
            ->findOrFail($modelId)
            ->append($appends);
    }
}
