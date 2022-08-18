<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

Builder::macro('paginationOrCollection', function () {
    $this->when(request()->get('list_type') == 'collection',
        function ($query): Collection {
            $limit = request()->get('limit', config('app.page_size'));
            (array)$appends = request()->get('appends', []);
            $pluck = request()->get('pluck');

            $query = $query->when($limit !== 'all', fn($q) => $q->limit($limit));

            if ($pluck) {
                if (is_array($pluck)) {
                    $column = $pluck['column'];
                    $key    = $pluck['key'] ?? null;
                } else {
                    $column = $pluck;
                    $key    = null;
                }

                return $query->pluck($column, $key);
            } else {
                return $query->get()
                    ->append($appends);
            }
        },
        function (Builder $query): LengthAwarePaginator {
            (int)$per_page = request()->get('per_page', config('app.pagination_size'));

            return $this->availability($query)
                ->paginate($per_page);
        });

    return $this;
});
