<?php

use Carbon\Carbon;
use Illuminate\Database\{
    Eloquent\Builder as EloquentBuilder,
    Query\Builder as QueryBuilder
};

QueryBuilder::macro('searchBy', function (array|null $searchBy = null) {
    $searchBy = $searchBy ?? request(config('laravel_api.params.search_by', 'search_by'));
    $this->when($searchBy, function (QueryBuilder $query) use ($searchBy) {
        if (!is_array($searchBy)) {
            throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.search_by', 'search_by')]));
        }
        $query->where(function (QueryBuilder $query) use ($searchBy) {
            foreach ($searchBy as $column => $search) {
                $query->orWhereLike($column, $search);
            }
        });
    });

    return $this;
});

EloquentBuilder::macro('searchBy', function (array|null $searchBy = null) {
    $searchBy = $searchBy ?? request(config('laravel_api.params.search_by', 'search_by'));
    $this->when($searchBy, function (EloquentBuilder $query) use ($searchBy) {
        if (!is_array($searchBy)) {
            throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.search_by', 'search_by')]));
        }
        $query->where(function (EloquentBuilder $query) use ($searchBy) {
            foreach ($searchBy as $column => $search) {
                $search = rtrim($search, " \t.");
                if ($query->isTranslatable($column)) {
                    foreach (config('laravel_api.available_locales',[]) as $lang) {
                        $query->orWhereLike("$column->$lang", $search);
                    }
                } elseif ($query->inDates($column)) {
                    $time = Carbon::createFromTimestamp(strtotime($search));
                    $query->orWhereDate($column, $time);
                } elseif (str_contains($column, '.')) {
                    $relation = explode('.', $column);
                    $column   = array_pop($relation);
                    $query->orWhereLikeRelation(implode('.', $relation), $column, $search);
                } else {
                    $query->orWhereLike($column, $search);
                }
            }
        });
    });

    return $this;
});
