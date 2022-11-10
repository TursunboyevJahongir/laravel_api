<?php

namespace App\Core\Repositories;

use App\Core\Contracts\CoreRepositoryContract;
use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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

    public function index(EloquentBuilder|Relation|null $query = null): Collection|LengthAwarePaginator
    {
        return $this->model->query()
            ->eloquentQuery($query)
            ->between()
            ->notBetween()
            ->filters()
            ->filters()
            ->orFilters()
            ->notFilters()
            ->when(!$query, function ($query) {
                $query->closure($this, 'availability')->closure($this, 'appends');
            })
            ->search()
            ->searchBy()
            ->isActive()
            ->sortBy()
            ->paginationOrCollection();
    }

    /**
     * for any checks
     *
     * @param EloquentBuilder|Model $query
     *
     * @return void
     */
    public function availability(
        EloquentBuilder|Model $query
    ): void {
    }

    public function appends(EloquentBuilder $query): void
    {
    }

    public function indexDb(QueryBuilder $query): Collection|LengthAwarePaginator
    {
        return \DB::query()
            ->dbQuery($query)
            ->between()
            ->notBetween()
            ->filters()
            ->orFilters()
            ->notFilters()
            ->searchBy()
            ->isActive()
            ->sortBy()
            ->paginationOrCollection();
    }

    /**
     * Show entity
     *
     * @param mixed $value
     * @param string|null $column
     * @param EloquentBuilder|Relation|null $query
     *
     * @return Model|null
     */
    public function show(
        mixed $value,
        string $column = null,
        EloquentBuilder|Relation $query = null
    ): ?Model {
        if ($value instanceof Model) {
            $column = $value->getKeyName();
            $value  = $value->{$value->getKeyName()};
        }
        $column = $column ?? $this->model->getKeyName();

        return $this->firstBy($value, $column, $query);
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
        EloquentBuilder|Relation $query = null
    ): ?Model {
        return $this->model->eloquentQuery(query: $query)
            ->when(!$query, function ($query) {
                $query->closure($this, 'availability');
            })
            ->where($column, $value)
            ->firstOrFail()
            ->append(request()->get('appends', []));
    }

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
    ) {
        if ($query = \DB::query()->dbQuery($query)->where($column, $value)->first()) {
            return $query;
        }

        throw new \Exception(__('errors.no_records'), 404);
    }
}
