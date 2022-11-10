<?php

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

foreach ([EloquentBuilder::class, QueryBuilder::class] as $builder) {
    $builder::macro('isActive', function (bool $status = null) {
        $status = request()->get('is_active', $status);

        $this->when(!is_null($status), function (EloquentBuilder|QueryBuilder $query) use ($status) {
            $acceptable = [true, false, 0, 1, '0', '1'];

            if (!in_array($status, $acceptable, true)) {
                throw new \Exception(__('validation.array', ['attribute' => 'is_active']));
            }
            $query->where('is_active', $status);
        });

        return $this;
    });

    $builder::macro('active', function () {
        $this->where('is_active', '=', true);

        return $this;
    });
}
