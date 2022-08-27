<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('isJson', function (string $field):bool {
        return method_exists($this,'getJsonColumns') &&
            in_array($field, $this->model->getJsonColumns(), true);
    });
}
