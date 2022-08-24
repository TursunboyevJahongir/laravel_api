<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;


EloquentBuilder::macro('searchBy', function (array $search) {
    foreach ($search as $key => $value) {
        $value = rtrim($value, " \t.");

        if (str_contains($key, '.')) {
            $relation = explode('.', $key);
            $column   = array_pop($relation);
            $this->orWhereRelation(implode('.', $relation), $column, "like", "%$value%");
        }else{
            $this->where(function (Builder $query) use ($key, $value) {
                $query->orWhere($key, "like", "%$value%");
            });
        }
    }
    return $this;
});
