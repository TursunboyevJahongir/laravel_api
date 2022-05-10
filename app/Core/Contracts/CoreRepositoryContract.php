<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Models\CoreModel;

interface CoreRepositoryContract
{
    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param int|null $status
     * @param int $start
     * @param string|null $search
     * @param array|null $filters
     * @param array|null $notFilters
     * @param string $filterBy
     * @param string $order
     *
     * @return Builder
     */
    public function mainQuery(
        array $columns = ['*'],
        array $relations = [],
        int|null $status = null,
        int $start = 1,
        string $search = null,
        array|null $filters = null,
        array|null $notFilters = null,
        string $filterBy = 'id',
        string $order = 'desc'
    ): Builder;

    public function collection(
        Builder $query,
        int|string $limit = 30,
        array $appends = []
    ): Collection;

    public function pagination(
        Builder $query,
        int $per_page = 30,
        array $appends = []
    ): LengthAwarePaginator;

    /**
     * фильтр доступности
     * availability filter
     * for example check system
     *
     * @param Builder|CoreModel $query
     *
     * @return Builder|CoreModel
     */
    public function availability(
        Builder|CoreModel $query,
    ): Builder|CoreModel;

    /**
     * Show entity
     *
     * @param CoreModel|int $model
     * @param string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return CoreModel|null
     */
    public function show(
        CoreModel|int $model,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?CoreModel;

    /**
     * Find element by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return CoreModel|null
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?CoreModel;

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
     * @param CoreModel|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(CoreModel|int $model, array $payload): bool;

    /**
     * Delete element
     *
     * @param CoreModel|int $model
     *
     * @return bool
     */
    public function delete(CoreModel|int $model): bool;

}
