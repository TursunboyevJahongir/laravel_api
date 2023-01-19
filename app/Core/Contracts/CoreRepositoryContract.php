<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\{Builder,
    Builder as EloquentBuilder,
    Model,
    Relations\Relation
};
use Illuminate\Database\Query\Builder as QueryBuilder;

interface CoreRepositoryContract
{
    public function index(EloquentBuilder|Relation|null $query = null): mixed;

    public function availability(
        EloquentBuilder|Model $query
    ): void;

    public function appends(EloquentBuilder $query): void;

    public function indexDb(QueryBuilder $query): mixed;

    /**
     * Show entity
     *
     * @param mixed $value
     * @param string|null $column
     * @param Builder|Relation|null $query
     *
     * @return Model|null
     */
    public function show(mixed $value, string $column = null, Builder|Relation $query = null): ?Model;

    /**
     * Create element
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function create(array $payload): mixed;

    /**
     * Update element
     *
     * @param Model|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(Model|int $model, array $payload): bool;

    /**
     * Delete element
     *
     * @param Model|int $model
     *
     * @return bool
     */
    public function delete(Model|int $model): bool;

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
        Builder|Relation $query = null,
        bool $fail = true
    ): ?Model;

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
        bool $fail = true
    );
}
