<?php


use Illuminate\Database\Eloquent\Collection;

Collection::macro('appends', function (array|string $appends = []) {
    $appends = $appends ?? request(config('laravel_api.request.appends', 'appends'), []);

    if (!is_array($appends) && !is_string($appends)) {
        throw new \Exception('appends must be an array or a string');
    }
    if (is_string($appends)) {
        $appends = explode(';', $appends);
    }

    if ($this instanceof Illuminate\Database\Eloquent\Collection) {
        $this->append($appends);
    }

    return $this;
});
