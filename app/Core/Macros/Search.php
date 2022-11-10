<?php

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

EloquentBuilder::macro('search', function (string|null $search = null) {
    $search = $search ?? request()->get('search');
    $this->when($search, function (Builder $query) use ($search) {
        if ($search && !is_string($search)) {
            throw new \Exception(__('validation.string', ['attribute' => 'search']));
        }
        $query->where(function (Builder $query) use ($search) {
            $search = rtrim($search, " \t.");
            foreach ($query->getSearchable() as $key => $field) {
                if (is_array($field)) {
                    $relation = $field[0];
                    foreach ($field[1] as $index => $value) {
                        if ($index === "json") {
                            foreach (AvailableLocalesEnum::toArray() as $lang) {
                                $query->orWhereLikeRelation(implode('.', $relation), "$value->$lang", $search);
                            }
                        } elseif ($index === "date") {
                            $time = Carbon::createFromTimestamp(strtotime($search));
                            $query->orWhereDate($index, $time);
                        } else {
                            $query->orWhereLikeRelation($relation, "$value->$lang", $search);
                        }
                    }
                } elseif (str_contains($field, '.')) {
                    $relation = explode('.', $field);
                    $column   = array_pop($relation);
                    $query->orWhereLikeRelation(implode('.', $relation), $column, $search);
                } elseif ($this->isJson($field)) {
                    foreach (AvailableLocalesEnum::toArray() as $lang) {
                        $query->orWhereLike("$field->$lang", $search);
                    }
                } elseif ($this->inDates($field)) {
                    $time = Carbon::createFromTimestamp(strtotime($search));
                    $query->orWhereDate($field, $time);
                } else {
                    $query->orWhereLike($field, $search);
                }
            }
        });
    });

    return $this;
});
