<?php

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('paginationOrCollection', function (): LengthAwarePaginator|Collection {
        return $this->when(request()->get('list_type') == 'collection',
            fn($q): Collection => $q->collection(),
            fn($q): LengthAwarePaginator => $q->pagination()
        );
    });
}
