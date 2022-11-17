<?php


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('pagination', function (): LengthAwarePaginator {
        (int)$per_page = request(config('laravel_api.request.per_page', 'per_page'), config('laravel_api.default.pagination_size'));

        return $this->paginate($per_page);
    });
}
