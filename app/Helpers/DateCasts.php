<?php

namespace App\Helpers;


use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Carbon;

class DateCasts implements CastsAttributes
{
    public function __construct(protected $format = 'Y-m-d H:i:s')
    {
    }

    public function get($model, $key, $value, $attributes)
    {
        return $value ? Carbon::create($value)->format($this->format) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return $value ? Carbon::parse($value) : null;
    }
}
