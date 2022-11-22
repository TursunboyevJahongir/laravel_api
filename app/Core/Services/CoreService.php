<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\{Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Contracts\{CoreRepositoryContract, CoreServiceContract};

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

    public function creating(FormRequest &$request): void
    {
    }

    /**
     * Create entity
     *
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function create(FormRequest $request): mixed
    {
        $this->creating($request);

        $model = $this->repository->create($request->validated());
        $this->created($model, $request);

        return $model;
    }

    public function created(Model $model, FormRequest $request): void
    {
    }

    public function updating(Model $model, FormRequest &$request): void
    {
    }

    /**
     * Update entity
     *
     * @param Model|int $model
     * @param FormRequest $request
     *
     * @return bool
     */
    public function update(Model|int $model, FormRequest $request): bool
    {
        $model = $this->repository->show($model);
        $this->updating($model, $request);
        $this->repository->update($model, $request->validated());
        $this->updated($model, $request);

        return true;
    }

    public function updated(Model $model, FormRequest $request): void
    {
    }

    /**
     * you can use Observer or this
     *
     * @param Model $model
     *
     * @return void
     */
    public function deleting(Model $model)
    {
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
        $this->deleting($model);

        return $this->repository->delete($model);
    }
}
