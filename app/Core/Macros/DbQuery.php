<?php

use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;

QueryBuilder::macro('dbQuery', function (
    QueryBuilder $query,
    array $columns = null,
): QueryBuilder {
    $validator = validator()->make(request()->all(), [
        config('laravel_api.params.columns', 'columns') => 'string',
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->messages()->toArray());
    }
    $requestColumns = request(config('laravel_api.params.columns', 'columns'), ['*']);

    if ($requestColumns !== ['*']) {
        $requestColumns = explode(',', $requestColumns);
    }
    $columns = $columns ?? $requestColumns;

    return $query->select($columns);
});
