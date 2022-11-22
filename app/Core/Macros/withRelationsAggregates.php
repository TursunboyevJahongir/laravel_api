<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('withRelationsAggregates', function (
    array|string $withSum = null,
    array|string $withMax = null,
    array|string $withMin = null,
    array|string $withAvg = null,
    array|string $withExists = null,
    array|string $withCount = null,
): Builder {
    if ($withSum = $withSum ?? request('withSum')) {
        withAggregateFormatter($withSum);
        foreach ($withSum as $item) {
            $this->withSum($item['relation'], $item['column']);
        }
    }
    if ($withMax = $withMax ?? request('withMax')) {
        withAggregateFormatter($withMax);
        foreach ($withMax as $item) {
            $this->withMax($item['relation'], $item['column']);
        }
    }
    if ($withMin = $withMin ?? request('withMin')) {
        withAggregateFormatter($withMin);
        foreach ($withMin as $item) {
            $this->withMin($item['relation'], $item['column']);
        }
    }
    if ($withAvg = $withAvg ?? request('withAvg')) {
        withAggregateFormatter($withAvg);
        foreach ($withAvg as $item) {
            $this->withMin($item['relation'], $item['column']);
        }
    }
    if ($withExists = $withExists ?? request('withExists')) {
        if (is_string($withExists)) {
            $withExists = explode(';', $withExists);
        }
        foreach ($withExists as $relation) {
            $this->withExists($relation);
        }
    }
    if ($withCount = $withCount ?? request('withCount')) {
        if (is_string($withCount)) {
            $withCount = explode(';', $withCount);
        }
        foreach ($withCount as $relation) {
            $this->withCount($relation);
        }
    }

    return $this;
});
