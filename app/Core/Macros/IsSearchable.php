<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

EloquentBuilder::macro('isSearchable', function (string $field): bool {
    return in_array($field, $this->getSearchable(), true);
});

EloquentBuilder::macro('getSearchable', function (): array {
    return method_exists($this->getModel(), 'getSearchable') ?
        $this->getModel()->getSearchable() : [];
});
