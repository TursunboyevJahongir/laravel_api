<?php

namespace App\Core\Contracts;

use App\Core\Http\Requests\GetAllFilteredRecordsRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CoreServiceContract
{
    public function get(GetAllFilteredRecordsRequest $request): Collection|LengthAwarePaginator;

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param FormRequest $request
     *
     * @return mixed
     */
    public function show(Model|int $model, FormRequest $request): mixed;

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
