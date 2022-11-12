<?php


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('collection', function (): Collection {
        $validator = validator()->make(request()->all(), [
            config('laravel_api.params.limit', 'limit')     => [function ($attribute, $value, $fail) {
                is_numeric($value) || $value === 'all' ? : $fail("$attribute must be numeric or 'all'");
            }],
            config('laravel_api.params.appends', 'appends') => 'array',
            config('laravel_api.params.pluck', 'pluck')     => !is_array(request(config('laravel_api.params.pluck', 'pluck'))) ? 'string' : "array|required_array_keys:column",
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->messages()->toArray());
        }
        $limit   = request(config('laravel_api.params.limit', 'limit'), config('laravel_api.default.page_size'));
        $appends = request(config('laravel_api.params.appends', 'appends'), []);
        $pluck   = request(config('laravel_api.params.pluck', 'pluck'));

        return $this->when($limit !== 'all', fn($q) => $q->limit($limit))
            ->when($pluck, function ($query) use ($pluck) {
                if (is_array($pluck)) {
                    $column = $pluck['column'];
                    $key    = $pluck['key'] ?? null;
                } else {
                    $column = $pluck;
                    $key    = null;
                }

                return $query->pluck($column, $key);
            }, function ($query) use ($appends) {
                return $query->get()->when(isEloquentModel($query), fn($q) => $q->append($appends));
            });
    });
}
