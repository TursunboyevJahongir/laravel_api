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
        config('laravel_api.params.columns', 'columns')           => 'string',
        config('laravel_api.params.relations', 'relations')       => 'array',
        config('laravel_api.params.only_deleted', 'only_deleted') => ['bool',
                                                                      function ($attribute, $value, $fail) {
                                                                          if (!hasPermission('system')) {
                                                                              $fail(__('messages.you_havnt_permission'));
                                                                          }
                                                                      }],
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->messages()->toArray());
    }

    $requestColumns = request(config('laravel_api.params.columns', 'columns'), ['*']);

    if ($requestColumns !== ['*']) {
        $requestColumns = explode(',', $requestColumns);
    }
    $columns   = $columns ?? $requestColumns;
    $relations = $relations ?? request(config('laravel_api.params.relations', 'relations'), []);
    $trashed   = $trashed ?? request(config('laravel_api.params.only_deleted', 'only_deleted'), false);

    return ($query ?? $this)
        ->select($columns)
        ->when($trashed, fn($query) => $query->onlyTrashed())
        ->with($relations);
});
