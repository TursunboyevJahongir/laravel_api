<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('whereInRelation', function ($relation, $column, array $value, $boolean = 'and') {
    return $this->whereHas($relation, function (Builder $query)
    use ($column, $value, $boolean) {
        $query->whereIn($column, $value, boolean: $boolean);
    });
});
