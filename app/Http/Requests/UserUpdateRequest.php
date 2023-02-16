<?php

namespace App\Http\Requests;

use App\Rules\PhoneRule;
use App\Rules\UniqueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'filled|string',
            'last_name'  => 'nullable|string',
            'phone'      => [
                'filled',
                new PhoneRule(),
                new UniqueRule('users', 'phone', auth()->user()->id),
            ],
            'password'   => ['filled', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'birthday'   => 'nullable|date|before_or_equal:' . now(),
            'is_active'  => 'bool',
            'avatar'     => 'image',
            'roles'      => 'array',
            'roles.*'    => 'nullable|exists:roles,name|not_in:superadmin',
        ];
    }
}
