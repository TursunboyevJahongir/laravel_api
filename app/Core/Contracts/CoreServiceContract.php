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

    public function show(Model|int $model): mixed;

    public function create(FormRequest|Validator $request): mixed;

    public function update(Model $model, FormRequest|Validator $request): bool;

    public function delete(Model|int $model): bool;
}
