<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
        $this->where(function (EloquentBuilder|QueryBuilder $query) use ($orderBy, $sort) {
            $orderBy = request()->get('order', $orderBy);
            $sort    = request()->get('sort', $sort);
            if (str_contains($orderBy, ',')) {
                $fields = explode(',', $orderBy);
                foreach ($fields as $field) {
                    $query->orderBy($field, $sort);
                }
            } else {
                $query->orderBy($orderBy, $sort);
            }
        });

        return $this;
    });
}
