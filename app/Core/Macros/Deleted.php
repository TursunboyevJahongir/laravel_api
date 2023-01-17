<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('onlyDeleted', function (bool $trashed = null): Builder {
    $trashed = $trashed ?? (bool)request(config('laravel_api.request.onlyDeleted', 'onlyDeleted'), false);

    $acceptable = [true, false, 0, 1, '0', '1'];

    if (!in_array($trashed, $acceptable, true)) {
        throw new \Exception(__('validation.boolean', ['attribute' => config('laravel_api.request.onlyDeleted', 'onlyDeleted'),
        ]));
    }

    return $this->when($trashed, fn($query) => $query->onlyTrashed());
});

Builder::macro('withDeleted', function (bool $trashed = null): Builder {
    $trashed = $trashed ?? (bool)request(config('laravel_api.request.withDeleted', 'withDeleted'), false);

    $acceptable = [true, false, 0, 1, '0', '1'];

    if (!in_array($trashed, $acceptable, true)) {
        throw new \Exception(__('validation.boolean', ['attribute' => config('laravel_api.request.withDeleted', 'withDeleted'),
        ]));
    }

    return $this->when($trashed, fn($query) => $query->withTrashed());
});
