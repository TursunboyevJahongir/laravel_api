<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function __;

class CheckJsonExistsLocale implements Rule
{
    /**
     * проверка существует на json locale lang
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return array_key_exists(config('laravel_api.main_locale'), $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('messages.check_json_exists_locale', ['attribute' => config('laravel_api.main_locale')]);
    }
}
