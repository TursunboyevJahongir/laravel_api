<?php

use Illuminate\Database\{
    Eloquent\Builder as EloquentBuilder,
    Query\Builder as QueryBuilder
};

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('whereLike', function (array|string $columns, string $search) {
        $search  = rtrim($search, " \t.");
        $columns = \Arr::wrap($columns);
        $this->where(function ($query) use ($columns, $search) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%$search%");
            }
        });

        return $this;
    });

    $builder::macro('orWhereLike', function (array|string $columns, string $search) {
        $search  = rtrim($search, " \t.");
        $columns = \Arr::wrap($columns);
        $this->orWhere(function ($query) use ($columns, $search) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', "%$search%");
            }
        });

        return $this;
    });
}

EloquentBuilder::macro('orWhereLikeRelation', function (string $relation, string $column, string $search) {
    $search = rtrim($search, " \t.");
    $this->orWhereRelation($relation, $column, "like", "%$search%");

    return $this;
});
