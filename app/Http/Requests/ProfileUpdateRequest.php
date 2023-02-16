<?php

namespace App\Http\Requests;

use App\Rules\PhoneRule;
use App\Rules\UniqueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => 'filled|string',
            'last_name'  => 'nullable|string',
            'phone'      => [
                'filled',
                new PhoneRule(),
                new UniqueRule('users', 'phone', $this->route('user', auth()->user())->id),
            ],
            'password'   => ['filled', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'avatar'     => 'image',
        ];
    }
}
