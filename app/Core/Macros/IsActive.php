<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('isActive', function (bool $status = null) {
        $status = request(config('laravel_api.params.is_active', 'is_active'), $status);

        $this->when(!is_null($status), function (EloquentBuilder|QueryBuilder $query) use ($status) {
            $acceptable = [true, false, 0, 1, '0', '1'];

            if (!in_array($status, $acceptable, true)) {
                throw new \Exception(__('validation.array', ['attribute' => config('laravel_api.params.is_active', 'is_active')]));
            }
            $query->where(config('laravel_api.check.is_active', 'is_active'), $status);
        });

        return $this;
    });

    $builder::macro('active', function () {
        $this->where(config('laravel_api.check.is_active', 'is_active'), '=', true);

        return $this;
    });
}
