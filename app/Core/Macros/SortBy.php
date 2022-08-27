<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
        $orderBy = request()->get('order', $orderBy);
        $sort    = request()->get('sort', $sort);

        if (str_contains($orderBy, ',') && $fields = explode(',', $orderBy)) {
            foreach ($fields as $field) {
                $this->orderBy($this->isJson($field), $sort);
            }
        } else $this->orderBy($this->isJson($orderBy), $sort);

        return $this;
    });
}
