<?php

namespace App\Core\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Contracts\{CoreRepositoryContract, CoreServiceContract};
use App\Core\Models\CoreModel;
use Illuminate\Support\Facades\DB;

abstract class CoreService implements CoreServiceContract
{
    public function __construct(protected CoreRepositoryContract $repository)
    {
    }

    /**
     * @param FormRequest $request
     * @param mixed ...$appends
     *
     * @return Collection|LengthAwarePaginator
     */
    public function get(FormRequest $request, ...$appends): Collection|LengthAwarePaginator
    {
        return $this->repository->mainQuery($request->get('columns', ['*']),
                                            $request->get('relations', []),
                                            $request->get('status'),
                                            $request->get('search'),
                                            $request->get('filters'),
                                            $request->get('not_filters'),
                                            $request->get('or_filters'),
                                            $request->get('order', 'id'),
                                            $request->get('sort', 'desc'),
                                            $request->get('only_deleted', false))
            ->where(function ($query) use ($appends) {
                $this->appends($query, $appends);
            })
            ->when($request->get('list_type') == 'collection',
                fn($query) => $this->repository->collection($query,
                                                            $request->get('limit', config('app.page_size')),
                                                            $request->get('appends', [])),
                fn($query) => $this->repository->pagination($query,
                                                            $request->get('per_page', config('app.pagination_size'))));
    }

    public function appends(Builder $query, ...$appends)
    {
    }

    /**
     * Show entity
     *
     * @param CoreModel|int $model
     * @param FormRequest $request
     *
     * @return CoreModel|null
     */
    public function show(CoreModel|int $model, FormRequest $request): ?CoreModel
    {
        return $this->repository->show($model,
                                       $request->get('columns') ?? ['*'],
                                       $request->get('relations') ?? [],
                                       $request->get('appends') ?? []
        );
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
        $model = Db::transaction(function () use ($request) {
            $request = $this->creating($request);

            return $this->repository->create($request->validated());
        });

        $this->created($model, $request);

        return $model;
    }

    public function creating(FormRequest $request)
    {
        return $request;
    }

    public function created(Model|CoreModel $model, FormRequest $request): void
    {
    }

    /**
     * Update entity
     *
     * @param CoreModel|int $model
     * @param FormRequest $request
     *
     * @return bool
     */
    public function update(CoreModel|int $model, FormRequest $request): bool
    {
        $model = $this->repository->show($model);
        Db::transaction(function () use ($request, $model) {
            $request = $this->updating($model, $request);
            $this->repository->update($model, $request->validated());
            $this->updated($model, $request);
        });

        return true;
    }

    public function updating(Model|CoreModel $model, FormRequest $request): FormRequest
    {
        return $request;
    }

    public function updated(Model|CoreModel $model, FormRequest $request): void
    {
    }

    /**
     * Delete entity
     *
     * @param CoreModel|int $model
     *
     * @return mixed
     */
    public function delete(CoreModel|int $model): mixed
    {
        return $this->repository->delete($model);
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
