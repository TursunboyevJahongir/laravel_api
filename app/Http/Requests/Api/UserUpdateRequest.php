<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneRule;
use App\Rules\UniqueRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
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
            'avatar'     => 'image',
            'roles'      => 'array',
            'roles.*'    => 'nullable|exists:roles,name|not_in:superadmin',
        ];
    }
}
