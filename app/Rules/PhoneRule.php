<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^998\d{9}$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return trans('messages.phone_invalid_format');
    }
}
