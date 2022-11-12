<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Str;

EloquentBuilder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
    $orderBy = request(config('laravel_api.params.order_by', 'orderBy'), config('laravel_api.default.order_by', $orderBy));
    $sort    = request(config('laravel_api.params.sort_by', 'sortBy'), config('laravel_api.default.sort_by', $sort));
    if (!is_string($orderBy)) {
        throw new \Exception(__('validation.string', ['attribute' => config('laravel_api.params.order_by', 'orderBy')]));
    }
    if (!in_array($sort, ['desc', 'asc', 'DESC', 'ASC'])) {
        throw new \Exception(__('validation.in', ['attribute' => config('laravel_api.params.sort_by', 'sortBy')]));
    }

    $fields = explode(';', $orderBy);

    foreach ($fields as $index => $field) {
        if (str_contains($field, '.')) {
            if (count($relation = explode('.', $field)) > 2) {
                throw new \Exception('Does not work multi-depth dot notation.Only works with one dot.');//todo need
                // change
            }
            $column       = array_pop($relation);
            $relation     = $relation[0];
            $relationData = $this->getModel()->{$relation}();

            if ($relationData instanceof BelongsTo) {
                $model        = $this->getModel();
                $relationData = (array)$relationData;
                array_pop($relationData);
                array_pop($relationData);
                $ownerKey   = array_pop($relationData);
                $foreignKey = array_pop($relationData);

                $table     = $model->{$relation}()->getModel()->getTable();
                $tableAs   = "$table-$index-postfix";
                $selfTable = $model->getTable();
                $this->leftJoin("$table as $tableAs", "$selfTable.$foreignKey", "$tableAs.$ownerKey")
                    ->when($columns = request(config('laravel_api.params.columns', 'columns'), ['*']) !== ['*'],
                        function ($query) use ($columns, $selfTable) {
                            $columns = array_map(function ($column) use ($selfTable) {
                                return "$selfTable.$column";
                            }, (array)$columns);
                            $query->select($columns);
                        },
                        fn($query) => $query->select(["$selfTable.*"]))
                    ->orderBy("$tableAs.$column", $sort);
            }
        } else {
            $this->orderBy($this->jsonLang($field), $sort);
        }
    }

    return $this;
});

QueryBuilder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
    $orderBy = request(config('laravel_api.params.order_by', 'orderBy'), config('laravel_api.default.order_by', $orderBy));
    $sort    = request(config('laravel_api.params.sort_by', 'sortBy'), config('laravel_api.default.sort_by', $sort));
    if (!is_string($orderBy)) {
        throw new \Exception(__('validation.string', ['attribute' => config('laravel_api.params.order_by', 'orderBy')]));
    }
    if (!in_array($sort, ['desc', 'asc', 'DESC', 'ASC'])) {
        throw new \Exception(__('validation.in', ['attribute' => config('laravel_api.params.sort_by', 'sortBy')]));
    }

    if (str_contains($orderBy, ';')) {
        $fields = explode(';', $orderBy);
        foreach ($fields as $field) {
            $this->orderBy($field, $sort);
        }
    } else {
        $this->orderBy($orderBy, $sort);
    }

    //foreach ($fields as $field) {
    //    if (str_contains($field, ';')) {
    //        $table  = explode(';', $field)[0];
    //        $key    = Str::singular($table) . "_id";
    //        $column = explode(':', $field)[1];
    //        if ((($table == 'products' || $table == 'categories' || $table == 'attributes') && $column == 'name') ||
    //            (($table == 'products' || $table == 'categories') && $column == 'description')) {
    //            $column = "$column->" . app()->getLocale();
    //        }
    //        $selfTable = $this->getTable();
    //        $this->leftJoin($table, "$selfTable.$key", "$table.id")
    //            ->when($columns = request(config('laravel_api.params.columns', 'columns'), ['*']) !== ['*'],
    //                function ($query) use ($columns, $selfTable) {
    //                    $columns = array_map(function ($column) use ($selfTable) {
    //                        return "$selfTable.$column";
    //                    }, (array)$columns);
    //                    $query->select($columns);
    //                },
    //                fn($query) => $query->select(["$selfTable.*"]))
    //            ->orderBy("$table.$column", $sort);
    //    } else {
    //        $this->orderBy($this->jsonLang($field), $sort);
    //    }
    //}

    return $this;
});
