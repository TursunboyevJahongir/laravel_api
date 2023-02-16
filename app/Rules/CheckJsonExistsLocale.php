<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use function __;

class CheckJsonExistsLocale implements Rule
{
    /**
     * проверка существует на json locale lang
     */
    public function passes($attribute, $value): bool
    {
        return array_key_exists(config('laravel_api.main_locale'), $value);
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('messages.check_json_exists_locale', ['attribute' => config('laravel_api.main_locale')]);
    }
}
