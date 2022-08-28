<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

interface CoreRepositoryContract
{
    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $trashed
     * @param Builder|null $query
     *
     * @return Builder
     */
    public function query(
        array $columns = ['*'],
        array $relations = [],
        bool $trashed = false,
        Builder|null $query = null
    ): Builder;

    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $trashed
     * @param Builder|null $query
     *
     * @return Builder
     */
    public function mainQuery(
        array $columns = ['*'],
        array $relations = [],
        bool $trashed = false,
        Builder|null $query = null
    ): Builder;

    /**
     * Show entity
     *
     * @param mixed $value
     * @param string $column
     *
     * @return Model|null
     */
    public function show(mixed $value, string $column = 'id'): ?Model;

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
        string $column = 'id'
    ): ?Model;
}
