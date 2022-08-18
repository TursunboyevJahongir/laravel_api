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

    /**
     * @param GetAllFilteredRecordsRequest $request
     * @param mixed ...$appends
     *
     * @return Collection|LengthAwarePaginator
     */
    public function get(GetAllFilteredRecordsRequest $request, ...$appends): Collection|LengthAwarePaginator
    {
        return $this->repository->query()
            ->isActive()
            ->where(\Closure::fromCallable([$this, 'appends']))
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
        return $this->repository->show($model,
                                       $request->get('columns') ?? ['*'],
                                       $request->get('relations') ?? [],
                                       $request->get('appends') ?? []
        );
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
        $model = $this->repository->show($model);//check availability
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
        $model = $this->repository->show($model);//check availability

        return Db::transaction(function () use ($model) {
            $this->deleting($model);

            return $this->repository->delete($model);
        });
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function findById(int $id): mixed
    {
        return $this->repository->findById($id);
    }
}
