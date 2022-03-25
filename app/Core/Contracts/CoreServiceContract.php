<?php

namespace App\Core\Contracts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Models\CoreModel;

interface CoreServiceContract
{
    public function get(FormRequest $request): Collection|LengthAwarePaginator;

    /**
     * Show entity
     *
     * @param CoreModel|int $model
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function show(CoreModel|int $model, FormRequest $request): mixed;

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
     * @param CoreModel $model
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function update(CoreModel $model, FormRequest $request): mixed;

    /**
     * Delete entity
     *
     * @param CoreModel $model
     *
     * @return mixed
     */
    public function delete(CoreModel $model): mixed;

    /**
     * Find entity by id
     *
     * @param int $id
     *
     * @return mixed
     */
    public function findById(int $id): mixed;
}
