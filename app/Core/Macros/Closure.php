<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('closure', function ($where, string $status) {
        $this->where(\Closure::fromCallable([$where, $status]));

        return $this;
    });
}
