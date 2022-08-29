<?php

use Illuminate\Database\Query\Builder as QueryBuilder;

QueryBuilder::macro('dbQuery', function (
    QueryBuilder $query,
    array $columns = null,
): QueryBuilder {
    $columns = $columns ?? request()->get('columns', ['*']);

    return $query->select($columns);
});
