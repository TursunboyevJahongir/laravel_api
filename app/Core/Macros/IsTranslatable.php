<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

EloquentBuilder::macro('jsonTranslate', function (string $field, string $lang = null): string {
    $lang = $lang ?? app()->getLocale();

    return $this->isTranslatable($field) ? "$field->$lang" : $field;
});

EloquentBuilder::macro('isTranslatable', function (string $field): bool {
    return method_exists($this->getModel(), 'getTranslatableColumns') &&
        in_array($field, $this->getModel()->getTranslatableColumns(), true);
});
