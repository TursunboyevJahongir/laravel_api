<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     */
    public function passes($attribute, $value): bool
    {
        return preg_match('/^998\d{9}$/', $value) === 1;
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return trans('messages.phone_invalid_format');
    }
}
