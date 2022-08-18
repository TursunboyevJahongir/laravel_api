<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

Builder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
    $this->where(function (Builder $query) use ($orderBy, $sort) {
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
