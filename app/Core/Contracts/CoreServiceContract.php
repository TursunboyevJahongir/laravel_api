<?php

namespace App\Core\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreServiceContract
{
    public function get(FormRequest $request): Collection|LengthAwarePaginator;

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function show(Model|int $model, FormRequest $request): mixed;

    /**
     * Create entity
     *
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function create(FormRequest $request): mixed;

    /**
     * Update entity
     *
     * @param Model $model
     * @param FormRequest $request
     *
     * @return bool
     */
    public function update(Model $model, FormRequest $request): bool;

    /**
     * Delete entity
     *
     * @param Model $model
     *
     * @return mixed
     */
    public function delete(Model $model): mixed;

    /**
     * Find entity by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function findById(int $id): mixed;
}
