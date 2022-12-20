<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\{Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

interface CoreServiceContract
{
    public function index(
        Builder|Relation|null $query = null
    ): mixed;

    public function indexDb(
        QueryBuilder $query
    ): mixed;

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

    public function creating(array &$data): void;

    /**
     * Create entity
     *
     * @param FormRequest|Validator $request
     *
     * @return mixed
     */
    public function create(FormRequest|Validator $request): mixed;

    public function created(Model $model, array $data): void;

    public function updating(Model $model, array &$data): void;

    /**
     * Update entity
     *
     * @param Model $model
     * @param FormRequest $request
     *
     * @return bool
     */
    public function update(Model $model, FormRequest|Validator $request): bool;

    public function updated(Model $model, array $data): void;

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
