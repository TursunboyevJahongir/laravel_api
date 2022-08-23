<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('whereInRelation', function ($relation, $column, array $value, $boolean = 'and') {
        return $this->whereHas($relation, function (EloquentBuilder|QueryBuilder $query)
        use ($column, $value, $boolean) {
            $query->whereIn($column, $value, boolean: $boolean);
        });
    });
}
