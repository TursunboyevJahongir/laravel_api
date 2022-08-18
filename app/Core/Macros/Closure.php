<?php

use Illuminate\Database\Eloquent\Builder;

Builder::macro('closure', function ($where,string $status) {
    $this->where(\Closure::fromCallable([$where, $status]));

    return $this;
});
