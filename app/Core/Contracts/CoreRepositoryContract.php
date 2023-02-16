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

    public function show(mixed $value, string $column = null, Builder|Relation $query = null): ?Model;

    public function create(array $payload): mixed;

    public function update(Model|int $model, array $payload): bool;

    public function delete(Model|int $model): bool;

    /**
     * Find element by column
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
