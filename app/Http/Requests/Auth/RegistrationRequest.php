<?php

namespace App\Http\Requests\Auth;

use App\Rules\PhoneRule;
use App\Rules\UniqueRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                new PhoneRule(),
                new UniqueRule('users','phone'),
            ],
            'first_name' => 'required|string',
            'last_name' => 'nullable|string',
            'password' => 'required|confirmed|min:6'
        ];
    }
}
