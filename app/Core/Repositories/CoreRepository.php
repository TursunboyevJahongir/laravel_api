<?php

namespace App\Core\Repositories;

use App\Core\Contracts\CoreRepositoryContract;
use Illuminate\Database\Eloquent\{Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;

abstract class CoreRepository implements CoreRepositoryContract
{
    public Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
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
        array $columns = null,
        array $relations = null,
        bool $trashed = null,
        Builder|Relation|null $query = null
    ): Builder|Relation {
        $columns   = $columns ?? request()->get('columns', ['*']);
        $relations = $relations ?? request()->get('relations', []);
        $trashed   = $trashed ?? request()->get('only_deleted', false);

        return ($query ?? $this->model)
            ->select($columns)
            ->closure($this, 'availability')
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->with($relations);
    }

    public function dbQuery(
        QueryBuilder $query,
        array $columns = null,
    ): QueryBuilder {
        $columns = $columns ?? request()->get('columns', ['*']);

        return $query->select($columns);
    }

    /**
     * Show entity
     *
     * @param mixed $value
     * @param string|null $column
     *
     * @return Model|null
     */
    public function show(
        mixed $value,
        string $column = null,
        Builder|Relation $query = null
    ): ?Model {
        if ($value instanceof Model) {
            $column = $value->getKeyName();
            $value  = $value->{$value->getKeyName()};
        }
        $column = $column ?? $this->model->getKeyName();

        return $this->firstBy($value, $column,$query);
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
        string $column = 'id',
        Builder|Relation $query = null
    ): ?Model {
        return $this->query(query: $query)
            ->where($column, $value)
            ->firstOrFail()
            ->append(request()->get('appends', []));
    }

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
    ) {
        if (!is_null($query = $this->dbQuery($query)
            ->where($column, $value)
            ->first())) {
            return $query;
        }

        throw new \Exception(__('errors.no_records'), 404);
    }
}
