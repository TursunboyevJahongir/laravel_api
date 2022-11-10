<?php


use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('collection', function (): Collection {
        $validator = validator()->make(request()->all(), [
            'limit'   => [function ($attribute, $value, $fail) {
                is_numeric($value) || $value === 'all' ? : $fail("$attribute must be numeric or 'all'");
            }],
            'appends' => 'array',
            'pluck'   => !is_array($this->get('pluck')) ? 'string' : "array|required_array_keys:column",
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->messages()->toArray());
        }
        $limit   = request()->get('limit', config('app.page_size'));
        $appends = request()->get('appends', []);
        $pluck   = request()->get('pluck');

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
