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
                    $field = $this->isJson($field) ?
                        $field . "->" . app()->getLocale() : $field;
                    $query->orderBy($field, $sort);
                }
            } else {
                $orderBy = $this->isJson($orderBy) ?
                    $orderBy . "->" . app()->getLocale() : $orderBy;
                $query->orderBy($orderBy, $sort);
            }
        });

        return $this;
    });
}
