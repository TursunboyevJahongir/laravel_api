<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('search', function ($columns, string $value) {
        $this->where(function ($query) use ($columns, $value) {
            foreach (Arr::wrap($columns) as $column) {
                $query->when(
                    Str::contains($column, '.'),

                    // Relational searches
                    function ($query) use ($column, $value) {
                        $parts          = explode('.', $column);
                        $relationColumn = array_pop($parts);
                        $relationName   = join('.', $parts);

                        return $query->orWhereHas(
                            $relationName,
                            function ($query) use ($relationColumn, $value) {
                                $query->where($relationColumn, 'LIKE', "%{$value}%");
                            }
                        );
                    },

                    // Default searches
                    function ($query) use ($column, $value) {
                        return $query->orWhere($column, 'LIKE', "%{$value}%");
                    }
                );
            }
        });

        return $this;
    });
}
