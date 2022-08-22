<?php

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * to filter filters[0][status]=activated&filters[0][name]="Jahongir"
 *
 * @param string $boolean
 */
EloquentBuilder::macro('filters', function ($filters = null, string $boolean = 'and') {
    $filters = $filters ?? request()->get('filters');
    $this->when($filters, function (Builder $query) use ($filters, $boolean) {
        $filters = $filters[array_key_first($filters)];
        foreach ($filters as $key => $filter) {
            if ($this->model->isSearchable($key)) {
                if ($this->model->isJson($key)) {
                    $query->where(function ($query) use ($key, $filter) {
                        foreach (AvailableLocalesEnum::toArray() as $lang) {
                            $query->orWhere("$key->$lang", "like", "%$filter%");
                        }
                    }, boolean: $boolean);
                } elseif ($this->model->inDates($key)) {
                    $time = Carbon::createFromTimestamp(strtotime($filter));
                    $query->whereDate($key, $time, $boolean);
                } else {
                    $query->where($key, 'like', "%$filter%", boolean: $boolean);
                }
            } elseif (in_array($key, $this->model->getDates(), true)) {
                $time = Carbon::createFromTimestamp(strtotime($filter));
                $query->whereDate($key, $time, $boolean);
            } elseif ($key === "id" || is_array($filter)) {
                $filter = is_array($filter) ? $filter : explode(',', $filter);
                $query->whereIn($key, $filter, boolean: $boolean);
            } elseif (str_contains($key, '.')) {
                $relation = explode('.', $key);
                $column   = array_pop($relation);
                $query->whereInRelation(implode('.', $relation), $column, Arr::wrap($filter), $boolean);
            } else {
                $query->where($key, '=', $filter, boolean: $boolean);
            }
        }
    });

    return $this;
});

/**
 * not equal
 * not filter not_filters[0][status]=activated
 */
EloquentBuilder::macro('orFilters', function ($orFilters = null) {
    $this->filters(request('or_filters', $orFilters), 'or');

    return $this;
});

/**
 * or filter
 * or_filters[0][first_name]=Jahongir&or_filters[0][last_name]=Jahongir&or_filters[0][middle_name]=Jahongir
 */
EloquentBuilder::macro('notFilters', function ($notFilters = null) {
    $this->whereNot(fn($q) => $q->filters(request('not_filters', $notFilters)));

    return $this;
});
