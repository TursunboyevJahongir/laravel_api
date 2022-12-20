<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('getBy', function (string $type = null): mixed {
        $getBy = $type ?? request(config('laravel_api.request.getBy', 'getBy'),
                config('laravel_api.default.getBy', 'pagination'));
        $column = null;
        if (str_contains($getBy, ':')) {
            [$getBy, $column] = explode(":", $getBy);
        }
        $inGetBy = config('laravel_api.in.getBy', ['pagination',
            'collection',
            'sum',
            'avg',
            'count',
            'max',
            'min',
            'exists',
            'doesntExists']);
        if (!in_array($getBy, $inGetBy)) {
            throw new \Exception(__('validation.in_array', ['attribute' => config('laravel_api.request.getBy', 'getBy'),
                'other' => implode(',', $inGetBy)]));
        }

        return match ($getBy) {
            'sum' => (float)$this->sum(DB::raw($column)),//sum:price*quantity
            'avg' => (float)$this->avg(DB::raw($column)),
            'count' => (int)$this->count($column ?? "*"),
            'max' => (float)$this->max(DB::raw($column)),
            'min' => (float)$this->min(DB::raw($column)),
            'exists' => (boolean)$this->exists(),
            'collection' => $this->collection(),
            default => $this->pagination(),
        };
    });
}
