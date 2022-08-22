<?php

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('filters', function ($filters,$boolean) {
        $this->where(function (Builder $query) use ($filters, $boolean) {
            $filters = $filters[array_key_first($filters)];
            foreach ($filters as $key => $filter) {
                if (in_array($key, $this->model->getFillable(), true)
                    || in_array($key, $this->model->getDates(), true)
                    || $key === "id") {
                    if ($this->model->isSearchable($key)) {
                        if ($this->model->isJson($key)) {
                            $query->where(function ($query) use ($key, $filter) {
                                foreach (AvailableLocalesEnum::toArray() as $lang) {
                                    $query->orWhere("$key->$lang", "like", "%$filter%");
                                }
                            });
                        } elseif ($this->model->inDates($key)) {
                            $time = Carbon::createFromTimestamp(strtotime($filter));
                            $query->orWhereDate($key, $time);
                        } else {
                            $query->where($key, 'like', "%$filter%", boolean: $boolean);
                        }
                    } elseif (in_array($key, $this->model->getDates(), true)) {
                        $time = Carbon::createFromTimestamp(strtotime($filter));
                        $query->orWhereDate($key, $time);
                    } elseif ($key === "id" || is_array($filter)) {
                        $filter = is_array($filter) ? $filter : explode(',', $filter);
                        $query->whereIn($key, $filter, boolean: $boolean);
                    } else {
                        $query->where($key, '=', $filter, boolean: $boolean);
                    }
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
}
