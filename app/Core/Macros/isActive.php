<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('isActive', function (bool $status = null) {
    $status = request()->get('isActive', $status);
    $this->when($status, function ($query) use ($status) {
        $query->where('is_active', $status);
    });

    return $this;
});
