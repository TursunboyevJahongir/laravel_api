<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;

EloquentBuilder::macro('eloquentQuery', function (
    EloquentBuilder|Relation|null $query = null,
    array $columns = null,
    array $relations = null,
    bool $trashed = null,
): EloquentBuilder|Relation {
    $columns   = $columns ?? request()->get('columns', ['*']);
    $relations = $relations ?? request()->get('relations', []);
    $trashed   = $trashed ?? request()->get('only_deleted', false);

    return ($query ?? $this)
        ->select($columns)
        ->when($trashed, fn($query) => $query->onlyTrashed())
        ->with($relations);
});
