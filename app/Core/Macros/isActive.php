<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('isActive', function (bool $status = null) {
        $status = request()->get('isActive', $status);
        $this->when($status, function ($query) use ($status) {
            $query->where('is_active', $status);
        });

        return $this;
    });
}
