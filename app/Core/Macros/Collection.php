<?php


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('collection', function (): Collection {
        $limit = request()->get('limit', config('app.page_size'));
        (array)$appends = request()->get('appends', []);
        $pluck = request()->get('pluck');

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
