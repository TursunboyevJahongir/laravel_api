<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

$EloquentBuilder = EloquentBuilder::class;
$queryBuilder    = QueryBuilder::class;

foreach ([$EloquentBuilder, $queryBuilder] as $builder) {
    $builder::macro('closure', function ($where, string $status) {
        $this->where(\Closure::fromCallable([$where, $status]));

        return $this;
    });
}
