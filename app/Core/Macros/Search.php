<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

Builder::macro('search', function (string|null $search = null, string|array $searchFields = null) {
    $search       = $search ?? request(config('laravel_api.request.search', 'search'));
    $searchFields = $searchFields ?? request(config('laravel_api.request.searchFields', 'searchFields'));

    if (is_string($searchFields)) {
        $searchFields = explode(',', $searchFields);
    }

    $this->when($search, function (Builder $query) use ($search, $searchFields) {
        if ($search && !is_string($search)) {
            throw new \Exception(__('validation.string', ['attribute' => config('laravel_api.request.search', 'search')]));
        }
        $query->where(function (Builder $query) use ($search, $searchFields) {
            $search = rtrim($search, " \t.");
            foreach ($searchFields ?? $query->getSearchable() as $column) {
                if (str_contains($column, '.')) {//todo error with date if has relation column
                    $relation = explode('.', $column);
                    $column   = array_pop($relation);
                    $query->orWhereHas(implode('.', $relation), function (Builder $query) use ($search, $column) {
                        if ($query->isTranslatable($column)) {
                            foreach (config('laravel_api.available_locales', []) as $lang) {
                                $query->orWhereLike("$column->$lang", $search);
                            }
                        } elseif ($query->inDates($column)) {
                            $time = Carbon::createFromTimestamp(strtotime($search));
                            $query->orWhereDate($column, $time);
                        } else {
                            $query->orWhereLike($column, $search);
                        }
                    });
                } elseif ($this->isTranslatable($column)) {
                    foreach (config('laravel_api.available_locales', []) as $lang) {
                        $query->orWhereLike("$column->$lang", $search);
                    }
                } elseif ($this->inDates($column)) {
                    $time = Carbon::createFromTimestamp(strtotime($search));
                    $query->orWhereDate($column, $time);
                } else {
                    $query->orWhereLike($column, $search);
                }
            }
        });
    });

    return $this;
});

QueryBuilder::macro('search', function (string|null $search = null, string|array $searchFields = null) {
    $search       = $search ?? request(config('laravel_api.request.search', 'search'));
    $searchFields = $searchFields ?? request(config('laravel_api.request.searchFields', 'searchFields'));

    if (is_string($searchFields)) {
        $searchFields = explode(',', $searchFields);
    }

    $this->when($search && $searchFields, function (QueryBuilder $query) use ($search, $searchFields) {
        $search = rtrim($search, " \t.");
        $query->where(function (QueryBuilder $query) use ($search, $searchFields) {
            foreach ($searchFields as $column) {
                $query->orWhereLike($column, $search);
            }
        });
    });

    return $this;
});
