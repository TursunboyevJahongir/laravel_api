<?php


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('collection', function (): Collection {
        $validator = validator()->make(request()->all(), [
            config('laravel_api.request.limit', 'limit') => [function ($attribute, $value, $fail) {
                is_numeric($value) || $value === 'all' ? : $fail("$attribute must be numeric or 'all'");
            }],
            config('laravel_api.request.pluck', 'pluck') => 'string',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->messages()->toArray());
        }
        $limit = request(config('laravel_api.request.limit', 'limit'), config('laravel_api.default.page_size'));

        $pluck = request(config('laravel_api.request.pluck', 'pluck'));

        return $this->when($limit !== 'all', fn($q) => $q->limit($limit))
            ->when($pluck, function (EloquentBuilder|QueryBuilder $query) use ($pluck) {
                [$key, $column] = str_contains($pluck, ':') ? explode(':', $pluck) : [null, $pluck];;

                if (str_contains($column, '.')) {
                    return $query->get()->pluck($column, $key);
                } else {
                    /**
                     * this is faster than $query->get()->pluck($column, $key); but The pluck method does not support extracting nested values using "dot" notation
                     */
                    return $query->pluck($column, $key);
                }
            }, function ($query) {
                return $query->get()->appends();
            });
    });
}
