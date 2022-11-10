<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Validation\ValidationException;

EloquentBuilder::macro('eloquentQuery', function (
    EloquentBuilder|Relation|null $query = null,
    array $columns = null,
    array $relations = null,
    bool $trashed = null,
): EloquentBuilder|Relation {
    $validator = validator()->make(request()->all(), [
        'columns'      => 'array',
        'relations'    => 'array',
        'only_deleted' => ['bool',
                           function ($attribute, $value, $fail) {
                               if (!hasPermission('system')) {
                                   $fail(__('messages.you_havnt_permission'));
                               }
                           }],
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->messages()->toArray());
    }

    $columns   = $columns ?? request()->get('columns', ['*']);
    $relations = $relations ?? request()->get('relations', []);
    $trashed   = $trashed ?? request()->get('only_deleted', false);

    return ($query ?? $this)
        ->select($columns)
        ->when($trashed, fn($query) => $query->onlyTrashed())
        ->with($relations);
});
