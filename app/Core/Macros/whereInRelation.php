<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

$EloquentBuilder = EloquentBuilder::class;
$queryBuilder    = QueryBuilder::class;

foreach ([$EloquentBuilder, $queryBuilder] as $builder) {
    $builder::macro('whereInRelation', function ($relation, $column, array $value, $boolean = 'and') {
        return $this->whereHas($relation, function ($query) use ($column, $value, $boolean) {
            $query->whereIn($column, $value, boolean: $boolean);
        });
    });
}
