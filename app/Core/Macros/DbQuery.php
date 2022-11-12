<?php

use Illuminate\Database\Query\Builder as QueryBuilder;

QueryBuilder::macro('dbQuery', function (
    QueryBuilder $query,
    array $columns = null,
): QueryBuilder {
    //validator()->make(request()->all(), [
    //    config('laravel_api.params.columns', 'columns') => 'array',
    //]);
    //$columns = $columns ?? request(config('laravel_api.params.columns', 'columns'), ['*']);
    //if (!is_array($columns)) {
    //    throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.columns', 'columns')]));
    //}

    return $query->select($columns);
});
