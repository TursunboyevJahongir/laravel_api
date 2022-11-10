<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\{Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreServiceContract
{
    public function index(
        Builder|Relation|null $query = null
    ): Collection|LengthAwarePaginator;

    public function indexDb(
        QueryBuilder $query
    ): Collection|LengthAwarePaginator;

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
    );

    /**
     * Show entity
     *
     * @param Model|int $model
     *
     * @return mixed
     */
    public function show(Model|int $model): mixed;

    public function creating(FormRequest &$request): void;

    /**
     * Create entity
     *
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function create(FormRequest $request): mixed;

    public function created(Model $model, FormRequest $request): void;

    public function updating(Model $model, FormRequest &$request): void;

    /**
     * Update entity
     *
     * @param Model $model
     * @param FormRequest $request
     *
     * @return bool
     */
    public function update(Model $model, FormRequest $request): bool;

    public function updated(Model $model, FormRequest $request): void;

    /**
     * you can use Observer or this
     *
     * @param Model $model
     *
     * @return void
     */
    public function deleting(Model $model);

    /**
     * Delete entity
     *
     * @param Model|int $model
     *
     * @return mixed
     */
    public function delete(Model|int $model): bool;
}
