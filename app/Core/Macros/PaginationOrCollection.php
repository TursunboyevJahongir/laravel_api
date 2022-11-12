<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('paginationOrCollection', function (): LengthAwarePaginator|Collection {
        $listType = request(config('laravel_api.params.list_type', 'list_type'), config('laravel_api.default.list_type', 'pagination'));
        if (!in_array($listType, ['pagination', 'collection'])) {
            throw new \Exception(__('validation.in', ['attribute' => config('laravel_api.params.list_type', 'list_type')]));
        }

        return $this->when($listType == 'collection',
            fn($q): Collection => $q->collection(),
            fn($q): LengthAwarePaginator => $q->pagination()
        );
    });
}
