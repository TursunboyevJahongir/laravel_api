<?php

namespace App\Core\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Support\Str;

abstract class CoreModel extends Model
{
    protected $json = [];

    protected $searchable = [];

    /**
     * Get the json attributes for the model.
     * @method getJsonColumns()
     * @return array
     */
    public function getJsonColumns()
    {
        return $this->json;
    }

    /**
     * Get the searchable attributes for the model.
     * @method getSearchable()
     * @return array
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    public function scopeWhereInRelation($query, $relation, $column, array $value)
    {
        return $query->whereHas($relation, function ($query) use ($column, $value) {
            $query->whereIn($column, $value);
        });
    }

    public function scopeOrWhereInRelation($query, $relation, $column, array $value)
    {
        return $query->orWhereHas($relation, function ($query) use ($column, $value) {
            $query->whereIn($column, $value);
        });
    }

    /**
     * it did to orderBy Column or relation column
     * если надо на каком нибудь запросе его надо изменить копируй его и поставь на модель и измени
     * if you need to change it on some request, copy it and put it on the model and change it
     *
     * @param Builder $query
     * @param string $filterBy
     * @param string $order
     * @param array $columns
     *
     * @return Builder|\Illuminate\Support\HigherOrderWhenProxy|mixed
     */
    public function scopeFilterBy(
        Builder $query,
        string $filterBy = "id",
        string $order = 'DESC',
        array $columns = ['*']
    ): Builder {
        if (str_contains($filterBy, ',')) {
            $fields = explode(',', $filterBy);
            foreach ($fields as $field) {
                $field = $this->isJson($field) ?
                    $field . "->" . app()->getLocale() : $field;
                $query->orderBy($field, $order);
            }
        } elseif (str_contains($filterBy, ':')) {
            $table  = explode(':', $filterBy)[0];
            $key    = Str::singular($table) . "_id";
            $column = explode(':', $filterBy)[1];
            if ((($table == 'products' || $table == 'categories' || $table == 'attributes') && $column == 'name') ||
                (($table == 'products' || $table == 'categories') && $column == 'description')) {
                $column = "$column->" . app()->getLocale();
            }
            $selfTable = $this->getTable();
            $query->leftJoin($table, "$selfTable.$key", "$table.id")
                ->when($columns !== ['*'],
                    function ($query) use ($columns, $selfTable) {
                        $columns = array_map(function ($column) use ($selfTable) {
                            return "$selfTable.$column";
                        }, $columns);
                        $query->select($columns);
                    },
                    fn($query) => $query->select(["$selfTable.*"]))
                ->orderBy("$table.$column", $order);
        } else {
            $filterBy = $this->isJson($filterBy) ?
                $filterBy . "->" . app()->getLocale() : $filterBy;
            $query->orderBy($filterBy, $order);
        }

        return $query;
    }

    public function isJson(string $field): bool
    {
        return in_array($field, $this->getJsonColumns(), true);
    }
}
