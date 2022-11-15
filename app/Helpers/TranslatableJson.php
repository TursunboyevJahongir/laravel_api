<?php

namespace App\Helpers;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TranslatableJson implements CastsAttributes
{
    public function get($model, $key, $value, $attributes)
    {
        return $value ? self::translatable($value, app()->getLocale()) : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }

    public static function translatable($attribute, $key = null)
    {
        $arr = json_decode($attribute, true);
        if (request()->has('edit_json')) {
            return $arr;
        }

        return $arr[$key] ?? $arr[config('laravel_api.main_locale')];
    }
}
