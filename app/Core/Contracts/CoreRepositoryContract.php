<?php

namespace App\Core\Contracts;

use App\Core\Models\CoreModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreRepositoryContract
{
    /**
     * @param array|string[] $columns
     * @param array $relations
     * @param int|null $status
     * @param string|null $search
     * @param array|null $filters
     * @param array|null $notFilters
     * @param array|null $orFilters
     * @param string $filterBy
     * @param string $order
     * @param bool $trashed
     *
     * @return Builder
     */
    public function mainQuery(
        array $columns = ['*'],
        array $relations = [],
        int|null $status = null,
        string $search = null,
        array|null $filters = null,
        array|null $notFilters = null,
        array|null $orFilters = null,
        string $filterBy = 'id',
        string $order = 'desc',
        bool $trashed = false
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
     * Show entity
     *
     * @param CoreModel|Model|int $model
     * @param string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return CoreModel|null
     */
    public function show(
        CoreModel|Model|int $model,
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
     * @param CoreModel|Model|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(CoreModel|Model|int $model, array $payload): bool;

    /**
     * Delete element
     *
     * @param CoreModel|Model|int $model
     *
     * @return bool
     */
    public function delete(CoreModel|Model|int $model): bool;
}
