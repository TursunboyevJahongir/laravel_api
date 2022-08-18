<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('isActive', function (bool $status = null) {
        $status = request()->get('isActive', $status);
        $this->when($status, function (EloquentBuilder|QueryBuilder $query) use ($status) {
            $query->where('is_active', $status);
        });

        return $this;
    });

    $builder::macro('active', function () {
        $this->where('is_active', '=', true);

        return $this;
    });
}
