<?php

use Illuminate\Database\{Eloquent\Builder as EloquentBuilder, Query\Builder as QueryBuilder};

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('inBetween', function (array $between) {
        $this->where(function (EloquentBuilder|QueryBuilder $query) use ($between) {
            foreach ($between as $column => $value) {
                $value .= str_contains($value, 'to') ? '' : 'to';
                [$from, $to] = explode("to", $value);
                if (isEloquent($query)) {
                    if (in_array($column, $this->model->getFillable(), true)
                        || in_array($column, $this->model->getDates(), true)
                        || $column === "id") {
                        $query->fromTo($column, $from, $to);
                    } elseif (str_contains($column, '.')) {
                        $relation = explode('.', $column);
                        $column   = array_pop($relation);
                        $query->whereHas(implode('.', $relation),
                            function ($query) use ($column, $from, $to) {
                                $query->fromTo($column, $from, $to);
                            });
                    }
                } else {
                    $query->fromTo($column, $from, $to);
                }
            }
        });

        return $this;
    });

    $builder::macro('fromTo', function ($column, $from, $to = null) {
        $this->where($column, '>=', $from)
            ->when($to, fn($q) => $q->where($column, '<=', $to));

        return $this;
    });

    /**
     * between
     * between[column]=fromTOto
     * between[column]=from
     * between[column]=TOto
     * between[amount]=200to400&between[price]=200&between[price]=,400
     */
    $builder::macro('between', function (array $between = null) {
        $between = $between ?? request(config('laravel_api.request.between', 'between'));
        $this->when($between, function ($query) use ($between) {
            if (!is_array($between)) {
                throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.request.between', 'between')]));
            }
            $query->inBetween($between);
        });

        return $this;
    });

    /**
     * not between
     * not_between[amount]=200to400&not_between[price]=200&not_between[price]=to400
     */
    $builder::macro('notBetween', function (array $notBetween = null) {
        $notBetween = $notBetween ?? request(config('laravel_api.request.not_between', 'not_between'));

        $this->when($notBetween, function ($query) use ($notBetween) {
            if (!is_array($notBetween)) {
                throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.request.not_between', 'not_between')]));
            }
            $query->whereNot(fn($q) => $q->inBetween($notBetween));
        });

        return $this;
    });
}


