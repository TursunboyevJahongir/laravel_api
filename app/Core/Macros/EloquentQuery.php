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
        config('laravel_api.request.columns', 'columns')           => 'string',
        config('laravel_api.request.relations', 'relations')       => 'string',
        config('laravel_api.request.only_deleted', 'only_deleted') => ['bool',
                                                                      function ($attribute, $value, $fail) {
                                                                          if (!hasPermission('system')) {
                                                                              $fail(__('messages.you_havnt_permission'));
                                                                          }
                                                                      }],
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->messages()->toArray());
    }

    $requestColumns   = request(config('laravel_api.request.columns', 'columns'), ['*']);
    $requestRelations = request(config('laravel_api.request.relations', 'relations'), []);

    if (is_string($requestColumns)) {
        $requestColumns = explode(',', $requestColumns);
    }
    if (is_string($requestRelations)) {
        $requestRelations = explode(';', $requestRelations);
    }
    $columns   = $columns ?? $requestColumns;
    $relations = $relations ?? $requestRelations;
    $trashed   = $trashed ?? request(config('laravel_api.request.only_deleted', 'only_deleted'), false);

    return ($query ?? $this)
        ->select($columns)
        ->when($trashed, fn($query) => $query->onlyTrashed())
        ->with($relations);
});
