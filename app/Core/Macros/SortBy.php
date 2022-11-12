<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Validation\ValidationException;

EloquentBuilder::macro('sortBy', function (string $orderBy = "id", string $sort = 'DESC') {
    $orderBy = request(config('laravel_api.params.order_by', 'orderBy'), config('laravel_api.default.order_by', $orderBy));
    $sort    = request(config('laravel_api.params.sort_by', 'sortBy'), config('laravel_api.default.sort_by', $sort));
    if (!is_string($orderBy)) {
        throw new \Exception(__('validation.string', ['attribute' => config('laravel_api.params.order_by', 'orderBy')]));
    }
    if (!in_array($sort, ['desc', 'asc', 'DESC', 'ASC'])) {
        throw new \Exception(__('validation.in', ['attribute' => config('laravel_api.params.sort_by', 'sortBy')]));
    }

    $validator = validator()->make(request()->all(), [
        config('laravel_api.params.columns', 'columns') => 'string',
    ]);

    if ($validator->fails()) {
        throw ValidationException::withMessages($validator->messages()->toArray());
    }
    $columns = request(config('laravel_api.params.columns', 'columns'), ['*']);

    if ($columns !== ['*']) {
        $columns = explode(',', $columns);
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
                    //todo has error with json translatable column
                    ->when($columns !== ['*'],
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

    return $this;
});
