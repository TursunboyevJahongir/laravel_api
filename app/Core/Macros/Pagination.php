<?php


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('pagination', function (): LengthAwarePaginator {
        (int)$per_page = request()->get('per_page', config('app.pagination_size'));

        return $this->paginate($per_page);
    });
}
