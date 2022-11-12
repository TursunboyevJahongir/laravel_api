<?php

use Illuminate\Database\{Eloquent\Builder as EloquentBuilder, Eloquent\Builder, Query\Builder as QueryBuilder};

/**
 * between
 * between[0][column]=from::to
 * between[0][amount]=200::400&between[0][price]=200&between[0][price]=::400
 */
EloquentBuilder::macro('between', function (array $between = null) {
    $between = $between ?? request(config('laravel_api.params.between', 'between'));
    $this->when($between, function ($query) use ($between) {
        if (!is_array($between)) {
            throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.between', 'between')]));
        }
        $query->where(function (Builder $query) use ($between) {
            $items = $between[array_key_first($between)];
            foreach ($items as $column => $value) {
                $value .= str_contains($value, '::') ? '' : '::';
                [$from, $to] = explode("::", $value);

                if (in_array($column, $this->model->getFillable(), true)
                    || in_array($column, $this->model->getDates(), true)
                    || $column === "id") {
                    $query->where($column, '>=', $from)
                        ->when($to, fn($q) => $q->where($column, '<=', $to));
                } elseif (str_contains($column, '.')) {
                    $relation = explode('.', $column);
                    $column   = array_pop($relation);
                    $query->whereHas(implode('.', $relation),
                        function ($query) use ($column, $from, $to) {
                            $query->where($column, '>=', $from)
                                ->when($to, fn($q) => $q->where($column, '<=', $to));
                        });
                }
            }
        });
    });

    return $this;
});

QueryBuilder::macro('between', function (array $between = null) {
    $between = $between ?? request(config('laravel_api.params.between', 'between'));
    if (!is_array($between)) {
        throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.between', 'between')]));
    }

    $this->where(function (Builder $query) use ($between) {
        $items = $between[array_key_first($between)];
        foreach ($items as $column => $value) {
            $value .= str_contains($value, '::') ? '' : '::';
            [$from, $to] = explode("::", $value);
            $this->whereBetween($query, $column, $from, $to);
        }
    });

    return $this;
});

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    /**
     * not between
     * not_between[0][amount]=200::400&not_between[0][price]=200&not_between[0][price]=::400
     */
    $builder::macro('notBetween', function (array $notBetween = null) {
        $notBetween = $notBetween ?? request(config('laravel_api.params.not_between', 'not_between'));

        $this->when($notBetween, function ($query) use ($notBetween) {
            if (!is_array($notBetween)) {
                throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.not_between', 'not_between')]));
            }
            $query->whereNot(fn($q) => $q->filters($notBetween));
        });

        return $this;
    });
}


