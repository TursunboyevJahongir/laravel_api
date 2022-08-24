<?php

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('search', function (string|null $search) {

        $search = rtrim($search, " \t.");

        $this->when($search,function (Builder $query) use ($search) {
            foreach ($this->model->getSearchable() as $key => $field) {
                if (is_array($field)) {
                    $relation = $field[0];
                    foreach ($field[1] as $index => $value) {
                        if ($index === "json") {
                            foreach (AvailableLocalesEnum::toArray() as $lang) {
                                $query->orWhereRelation($relation, "$value->$lang", "like", "%$search%");
                            }
                        } elseif ($index === "date") {
                            $time = Carbon::createFromTimestamp(strtotime($search));
                            $query->orWhereDate($index, $time);
                        } else {
                            $query->orWhereRelation($relation, $value, 'like', "%$search%");
                        }
                    }
                } elseif (str_contains($field, '.')) {
                    $relation = explode('.', $field);
                    $column   = array_pop($relation);
                    $query->orWhereRelation(implode('.', $relation), $column, "like", "%$search%");
                } elseif ($this->model->isJson($field)) {
                    foreach (AvailableLocalesEnum::toArray() as $lang) {
                        $query->orWhere("$field->$lang", "like", "%$search%");
                    }
                } elseif ($this->model->inDates($field)) {
                    $time = Carbon::createFromTimestamp(strtotime($search));
                    $query->orWhereDate($field, $time);
                } else {
                    $query->orWhere($field, "like", "%$search%");
                }
            }
        });
        return $this;
    });
}
