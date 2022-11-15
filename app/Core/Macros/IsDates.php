<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\Cache;

EloquentBuilder::macro('inDates', function (string $field): bool {
    $dates = Cache::remember($this->getModel()->getTable() . 'inDates', 86400, function () {//60 * 60 * 24=day
        $keys = collect($this->getModel()->getCasts())
            ->filter(function ($value, $key) {
                if (str_contains($value, 'DateCasts')
                    || str_contains($value, 'datetime')
                    || str_contains($value, 'date')) {
                    return $key;
                }
            });

        return array_unique($keys->keys()->toArray() + $this->getModel()->getDates());
    });


    return in_array($field, $dates);
});
