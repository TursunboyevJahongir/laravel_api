<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('closure', function ($class, string $status) {
        $this->where(\Closure::fromCallable([$class, $status]));

        return $this;
    });
}
