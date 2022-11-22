<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('withRelations', function (array|string $relations = null): Builder {
    $requestRelations = request(config('laravel_api.request.relations', 'relations'), []);
    if (is_string($requestRelations)) {
        $requestRelations = explode(';', $requestRelations);
    }
    $relations = $relations ?? $requestRelations;

    return $this->with($relations);
});
