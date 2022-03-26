<?php

namespace App\Core\Services;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Contracts\{CoreRepositoryContract, CoreServiceContract};
use App\Core\Models\CoreModel;
use Throwable;

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
        $query = $this->repository->mainQuery($request->get('columns', ['*']),
                                              $request->get('relations', []),
                                              $request->get('status'),
                                              $request->get('start', 1),
                                              $request->get('search'),
                                              $request->get('filters'),
                                              $request->get('not_filters'),
                                              $request->get('filterBy', 'id'),
                                              $request->get('order', 'desc'));

        return match ($request->get('list_type')) {
            'collection' => $this->repository->collection($query,
                                                          $request->get('limit', config('app.page_size')),
                                                          $request->get('appends', [])),
            default => $this->repository->pagination($query,
                                                     $request->get('per_page', config('app.pagination_size')),
                                                     $request->get('appends', [])),
        };
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
        return $this->repository->create($request->validated());
    }

    /**
     * Update entity
     *
     * @param CoreModel $model
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function update(CoreModel $model, FormRequest $request): mixed
    {
        return $this->repository->update($model, $request->validated());
    }

    /**
     * Delete entity
     *
     * @param CoreModel $model
     *
     * @return mixed
     */
    public function delete(CoreModel $model): mixed
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
