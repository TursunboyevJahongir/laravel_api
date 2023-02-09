<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\{Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Http\FormRequest;
use App\Core\Contracts\{CoreRepositoryContract, CoreServiceContract};
use Illuminate\Validation\Validator;

abstract class CoreService implements CoreServiceContract
{
    public function __construct(protected CoreRepositoryContract $repository)
    {
    }

    public function index(
        Builder|Relation|null $query = null
    ): mixed {
        return $this->repository->index(query: $query);
    }

    public function indexDb(
        QueryBuilder $query
    ): mixed {
        return $this->repository->indexDb(query: $query);
    }

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
    ) {
        return $this->repository->dbFirstBy($query, $value, $column);
    }

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param Builder|Relation|null $query
     *
     * @return Model|null
     */
    public function show(Model|int $model, Builder|Relation $query = null): ?Model
    {
        return $this->repository->show($model, query: $query);
    }

    /**
     * Create entity
     *
     * @param FormRequest|Validator $request
     *
     * @return mixed
     */
    public function create(FormRequest|Validator $request): mixed
    {
        return $this->repository->create($request->validated());
    }

    /**
     * Update entity
     *
     * @param Model|int $model
     * @param FormRequest|Validator $request
     *
     * @return bool
     */
    public function update(Model|int $model, FormRequest|Validator $request): bool
    {
        $model = $this->repository->show($model);
        $this->repository->update($model, $request->validated());

        return true;
    }

    /**
     * Delete entity
     *
     * @param Model|int $model
     *
     * @return bool
     */
    public function delete(Model|int $model): bool
    {
        $model = $this->repository->show($model);

        return $this->repository->delete($model);
    }
}
