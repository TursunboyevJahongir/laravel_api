<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreRepositoryContract
{
    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $trashed
     * @param string $orderBy
     * @param string $sort
     * @param Builder|null $query
     *
     * @return Builder
     */
    public function query(
        array $columns = ['*'],
        array $relations = [],
        bool $trashed = false,
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder;

    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $trashed
     * @param string $orderBy
     * @param string $sort
     * @param Builder|null $query
     *
     * @return Builder
     */
    public function mainQuery(
        array $columns = ['*'],
        array $relations = [],
        bool $trashed = false,
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder;

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param string[] $columns
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
    ): ?Model;

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
    ): ?Model;

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
}
