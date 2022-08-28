<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

EloquentBuilder::macro('jsonLang', function (string $field, string $lang = null): string {
    $lang = $lang ?? app()->getLocale();

    return $this->isJson($field) ? "$field->$lang" : $field;
});

EloquentBuilder::macro('isJson', function (string $field): bool {
    return method_exists($this->getModel(), 'getJsonColumns') &&
        in_array($field, $this->getModel()->getJsonColumns(), true);
});
