<?php

use Illuminate\Database\Query\Builder as QueryBuilder;

QueryBuilder::macro('dbQuery', function (
    QueryBuilder $query,
    array $columns = null,
): QueryBuilder {
    validator()->make(request()->all(), [
        'columns' => 'array',
    ]);
    $columns = $columns ?? request()->get('columns', ['*']);
    if (!is_array($columns)) {
        throw new \Exception(__('validation.array', ['attribute' => 'columns']));
    }

    return $query->select($columns);
});
