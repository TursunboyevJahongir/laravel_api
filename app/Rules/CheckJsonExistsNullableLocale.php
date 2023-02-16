<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use function __;
use function config;

class CheckJsonExistsNullableLocale implements Rule
{
    /**
     * проверка существует на json locale lang
     */
    public function passes($attribute, $value): bool
    {
        if (!array_key_exists(config('laravel_api.main_locale'), $value)) {
            return false;
        }

        if ($value[config('laravel_api.main_locale')]) {
            return true;
        }
        unset($value[config('laravel_api.main_locale')]);
        foreach ($value as $item) {
            if ((bool)$item) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('messages.check_json_exists_locale', ['attribute' => config('laravel_api.main_locale')]);
    }
}
