<?php

namespace App\Core\Services;

use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Contracts\{CoreRepositoryContract, CoreServiceContract};
use Illuminate\Support\Facades\DB;

abstract class CoreService implements CoreServiceContract
{
    public function __construct(protected CoreRepositoryContract $repository)
    {
    }

    public function get(GetAllFilteredRecordsRequest $request): Collection|LengthAwarePaginator
    {
        return $this->repository
            ->query()
            ->filters()
            ->orFilters()
            ->notFilters()
            ->search()
            ->searchBy()
            ->isActive()
            ->closure($this, 'appends')
            ->sortBy()
            ->paginationOrCollection();
    }

    public function appends(Builder $query)
    {
    }

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param FormRequest $request
     *
     * @return Model|null
     */
    public function show(Model|int $model, FormRequest $request): ?Model
    {
        return $this->repository->show($model);
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
     * @return mixed
     */
    public function delete(Model|int $model): mixed
    {
        return Db::transaction(function () use ($model) {
            $this->deleting($model);

            return $this->repository->delete($model);
        });
    }
}
